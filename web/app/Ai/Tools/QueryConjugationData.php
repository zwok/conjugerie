<?php

namespace App\Ai\Tools;

use App\Models\Conjugation;
use App\Models\Group;
use App\Models\StudentAnswer;
use App\Models\Tense;
use App\Models\User;
use App\Models\Verb;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

class QueryConjugationData implements Tool
{
    public function description(): string
    {
        return 'Query the conjugation database. Can retrieve verbs, tenses, conjugations, student answers, users, groups, and statistics like success rates and leaderboards.';
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
            'limit' => Type::integer()->nullable(),
        ];
    }

    public function handle(Request $request): string
    {
        return match ($request['query_type']) {
            'verbs' => $this->getVerbs(),
            'tenses' => $this->getTenses(),
            'conjugations_for_verb' => $this->getConjugationsForVerb($request['verb'] ?? null),
            'verb_stats' => $this->getVerbStats($request['limit'] ?? 20),
            'student_stats' => $this->getStudentStats($request['group'] ?? null),
            'group_stats' => $this->getGroupStats(),
            'leaderboard' => $this->getLeaderboard($request['group'] ?? null, $request['limit'] ?? 10),
            'difficult_conjugations' => $this->getDifficultConjugations($request['tense'] ?? null, $request['limit'] ?? 10),
            'overview' => $this->getOverview(),
            default => 'Unknown query type.',
        };
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

    private function getVerbStats(int $limit): string
    {
        $verbs = Verb::with('conjugations.studentAnswers')
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

    private function getStudentStats(?string $group): string
    {
        $query = User::where('is_teacher', false)
            ->withCount([
                'studentAnswers',
                'studentAnswers as correct_count' => fn ($q) => $q->where('is_correct', true),
            ]);

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
        ]);

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

    private function getLeaderboard(?string $group, int $limit): string
    {
        $query = User::where('is_teacher', false)
            ->withCount([
                'studentAnswers as correct_count' => fn ($q) => $q->where('is_correct', true),
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

    private function getDifficultConjugations(?string $tense, int $limit): string
    {
        $query = Conjugation::with(['verb', 'tense'])
            ->withCount([
                'studentAnswers',
                'studentAnswers as incorrect_count' => fn ($q) => $q->where('is_correct', false),
            ])
            ->whereHas('studentAnswers')
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

    private function getOverview(): string
    {
        return json_encode([
            'total_verbs' => Verb::count(),
            'total_tenses' => Tense::count(),
            'total_conjugations' => Conjugation::count(),
            'enabled_conjugations' => Conjugation::where('enabled', true)->count(),
            'total_students' => User::where('is_teacher', false)->count(),
            'total_answers' => StudentAnswer::count(),
            'correct_answers' => StudentAnswer::where('is_correct', true)->count(),
            'overall_success_rate' => StudentAnswer::count() > 0
                ? round(StudentAnswer::where('is_correct', true)->count() / StudentAnswer::count() * 100, 1) . '%'
                : 'N/A',
            'total_groups' => Group::count(),
        ]);
    }
}
