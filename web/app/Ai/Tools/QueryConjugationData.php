<?php

namespace App\Ai\Tools;

use App\Models\Conjugation;
use App\Models\Group;
use App\Models\StudentAnswer;
use App\Models\Tense;
use App\Models\User;
use App\Models\Verb;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

class QueryConjugationData implements Tool
{
    public function description(): string
    {
        return 'Query the conjugation database. Can retrieve verbs, tenses, conjugations, student answers, users, groups, and statistics like success rates and leaderboards. Use "since" and "until" (YYYY-MM-DD) to filter by time period. Use "user" to filter by student name.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query_type' => Type::string()->enum([
                'verbs',
                'tenses',
                'conjugations_for_verb',
                'verb_stats',
                'student_stats',
                'group_stats',
                'leaderboard',
                'difficult_conjugations',
                'overview',
            ]),
            'verb' => Type::string()->nullable(),
            'tense' => Type::string()->nullable(),
            'group' => Type::string()->nullable(),
            'user' => Type::string()->nullable(),
            'limit' => Type::integer()->nullable(),
            'since' => Type::string()->nullable(),
            'until' => Type::string()->nullable(),
        ];
    }

    public function handle(Request $request): string
    {
        $since = $request['since'] ?? null;
        $until = $request['until'] ?? null;
        $user = $request['user'] ?? null;

        return match ($request['query_type']) {
            'verbs' => $this->getVerbs(),
            'tenses' => $this->getTenses(),
            'conjugations_for_verb' => $this->getConjugationsForVerb($request['verb'] ?? null),
            'verb_stats' => $this->getVerbStats($request['limit'] ?? 20, $since, $until, $user),
            'student_stats' => $this->getStudentStats($request['group'] ?? null, $since, $until, $user),
            'group_stats' => $this->getGroupStats(),
            'leaderboard' => $this->getLeaderboard($request['group'] ?? null, $request['limit'] ?? 10, $since, $until),
            'difficult_conjugations' => $this->getDifficultConjugations($request['tense'] ?? null, $request['limit'] ?? 10, $since, $until, $user),
            'overview' => $this->getOverview($since, $until),
            default => 'Unknown query type.',
        };
    }

    private function dateFilter(Builder $query, ?string $since, ?string $until): Builder
    {
        if ($since) {
            $query->where('created_at', '>=', $since);
        }
        if ($until) {
            $query->where('created_at', '<=', $until . ' 23:59:59');
        }

        return $query;
    }

    private function answerScope(?string $since, ?string $until, ?string $user = null): \Closure
    {
        return function ($q) use ($since, $until, $user) {
            if ($since) {
                $q->where('student_answers.created_at', '>=', $since);
            }
            if ($until) {
                $q->where('student_answers.created_at', '<=', $until . ' 23:59:59');
            }
            if ($user) {
                $q->whereHas('user', fn ($uq) => $uq->where('name', 'like', "%$user%"));
            }
        };
    }

    private function resolveUserId(?string $user): ?int
    {
        if (! $user) {
            return null;
        }

        return User::where('name', 'like', "%$user%")->value('id');
    }

    private function getVerbs(): string
    {
        $verbs = Verb::withCount('conjugations')->get(['id', 'infinitive', 'group']);

        return json_encode($verbs->toArray());
    }

    private function getTenses(): string
    {
        $tenses = Tense::withCount('conjugations')->get();

        return json_encode($tenses->toArray());
    }

    private function getConjugationsForVerb(?string $verb): string
    {
        if (! $verb) {
            return 'Please provide a verb infinitive.';
        }

        $verbModel = Verb::where('infinitive', $verb)->first();

        if (! $verbModel) {
            return "Verb '$verb' not found.";
        }

        $conjugations = $verbModel->conjugations()
            ->with('tense')
            ->get(['id', 'tense_id', 'person', 'conjugated_form', 'enabled'])
            ->groupBy('tense.name');

        return json_encode($conjugations->toArray());
    }

    private function getVerbStats(int $limit, ?string $since, ?string $until, ?string $user): string
    {
        $scope = $this->answerScope($since, $until, $user);

        $verbs = Verb::with(['conjugations.studentAnswers' => $scope])
            ->get()
            ->map(function ($verb) {
                $answers = $verb->conjugations->flatMap->studentAnswers;
                $total = $answers->count();
                $correct = $answers->where('is_correct', true)->count();
                $incorrect = $total - $correct;

                return [
                    'verb' => $verb->infinitive,
                    'group' => $verb->group,
                    'total_answers' => $total,
                    'correct' => $correct,
                    'incorrect' => $incorrect,
                    'success_rate' => $total > 0
                        ? round($correct / $total * 100, 1) . '%'
                        : 'N/A',
                ];
            })
            ->filter(fn ($v) => $v['total_answers'] > 0)
            ->sortByDesc('total_answers')
            ->take($limit)
            ->values();

        return json_encode($verbs->toArray());
    }

    private function getStudentStats(?string $group, ?string $since, ?string $until, ?string $user): string
    {
        $scope = $this->answerScope($since, $until);

        $query = User::where('is_teacher', false)
            ->withCount([
                'studentAnswers' => $scope,
                'studentAnswers as correct_count' => function ($q) use ($scope) {
                    $scope($q);
                    $q->where('is_correct', true);
                },
            ]);

        if ($user) {
            $query->where('name', 'like', "%$user%");
        }
        if ($group) {
            $query->whereHas('mainGroup', fn ($q) => $q->where('name', 'like', "%$group%"));
        }

        $users = $query->get(['id', 'name'])->map(fn ($u) => [
            'name' => $u->name,
            'total_answers' => $u->student_answers_count,
            'correct' => $u->correct_count,
            'success_rate' => $u->student_answers_count > 0
                ? round($u->correct_count / $u->student_answers_count * 100, 1) . '%'
                : 'N/A',
        ])->filter(fn ($u) => $u['total_answers'] > 0)->values();

        return json_encode($users->toArray());
    }

    private function getGroupStats(): string
    {
        $groups = Group::withCount('users')->get()->map(fn ($g) => [
            'name' => $g->name,
            'year' => $g->year,
            'student_count' => $g->users_count,
        ]);

        return json_encode($groups->toArray());
    }

    private function getLeaderboard(?string $group, int $limit, ?string $since, ?string $until): string
    {
        $scope = $this->answerScope($since, $until);

        $query = User::where('is_teacher', false)
            ->withCount([
                'studentAnswers as correct_count' => function ($q) use ($scope) {
                    $scope($q);
                    $q->where('is_correct', true);
                },
            ])
            ->orderByDesc('correct_count')
            ->limit($limit);

        if ($group) {
            $query->whereHas('mainGroup', fn ($q) => $q->where('name', 'like', "%$group%"));
        }

        $users = $query->get(['id', 'name'])->map(fn ($u) => [
            'name' => $u->name,
            'correct_answers' => $u->correct_count,
        ]);

        return json_encode($users->toArray());
    }

    private function getDifficultConjugations(?string $tense, int $limit, ?string $since, ?string $until, ?string $user): string
    {
        $scope = $this->answerScope($since, $until, $user);

        $query = Conjugation::with(['verb', 'tense'])
            ->withCount([
                'studentAnswers' => $scope,
                'studentAnswers as incorrect_count' => function ($q) use ($scope) {
                    $scope($q);
                    $q->where('is_correct', false);
                },
            ])
            ->whereHas('studentAnswers', $scope)
            ->orderByDesc('incorrect_count')
            ->limit($limit);

        if ($tense) {
            $query->whereHas('tense', fn ($q) => $q->where('name', 'like', "%$tense%"));
        }

        $conjugations = $query->get()->map(fn ($c) => [
            'verb' => $c->verb->infinitive,
            'tense' => $c->tense->name,
            'person' => $c->person,
            'form' => $c->conjugated_form,
            'attempts' => $c->student_answers_count,
            'errors' => $c->incorrect_count,
            'error_rate' => round($c->incorrect_count / $c->student_answers_count * 100, 1) . '%',
        ]);

        return json_encode($conjugations->toArray());
    }

    private function getOverview(?string $since, ?string $until): string
    {
        $answersQuery = StudentAnswer::query();
        $this->dateFilter($answersQuery, $since, $until);

        $total = $answersQuery->count();
        $correct = (clone $answersQuery)->where('is_correct', true)->count();

        return json_encode([
            'total_verbs' => Verb::count(),
            'total_tenses' => Tense::count(),
            'total_conjugations' => Conjugation::count(),
            'enabled_conjugations' => Conjugation::where('enabled', true)->count(),
            'total_students' => User::where('is_teacher', false)->count(),
            'total_answers' => $total,
            'correct_answers' => $correct,
            'overall_success_rate' => $total > 0
                ? round($correct / $total * 100, 1) . '%'
                : 'N/A',
            'total_groups' => Group::count(),
            'period' => ($since || $until) ? ['since' => $since, 'until' => $until] : 'all time',
        ]);
    }
}
