<?php

namespace App\Livewire;

use App\Models\Conjugation;
use App\Models\ConjugationSet;
use App\Models\StudentAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ConjugationPractice extends Component
{
    #[Locked]
    public $currentConjugation = null;
    public $studentAnswer = '';
    #[Locked]
    public $infinitive = '';
    #[Locked]
    public $tense = '';
    #[Locked]
    public $person = '';
    #[Locked]
    public $conjugationSetId = null; // ID of the conjugation set to practice
    #[Locked]
    public string $setName = '';

    // Feedback state: '', 'correct', 'retry', 'reveal', 'copied' or 'empty' (no conjugations)
    #[Locked]
    public string $feedback = '';
    #[Locked]
    public string $praise = '';
    #[Locked]
    public string $correctForm = '';
    #[Locked]
    public int $lastXp = 0;

    // Attempts state
    #[Locked]
    public int $maxTries = 2;
    #[Locked]
    public int $remainingTries = 2;
    #[Locked]
    public bool $questionDone = false; // becomes true after a correct answer or after retyping the revealed answer
    #[Locked]
    public bool $revealed = false; // tries depleted: the answer is shown and must be retyped

    // Round state
    #[Locked]
    public int $roundSize = 10;
    #[Locked]
    public int $questionNumber = 1;
    #[Locked]
    public int $roundCorrect = 0;
    #[Locked]
    public int $roundXp = 0;
    #[Locked]
    public bool $roundFinished = false;
    #[Locked]
    public array $seenIds = [];

    // Consecutive answers that were correct without any mistake
    #[Locked]
    public int $combo = 0;
    #[Locked]
    public int $bestCombo = 0;

    protected const PRAISES = ['Bien joué !', 'Parfait !', 'Excellent !', 'Trop fort !', 'Nickel !', 'Bravo !'];

    public function mount()
    {
        $this->maxTries = max(1, (int) config('practice.max_attempts', 2));
        $this->roundSize = max(1, (int) config('practice.round_size', 10));

        if ($this->conjugationSetId) {
            $this->setName = ConjugationSet::find($this->conjugationSetId)?->name ?? '';
        }

        $this->loadQuestion();
    }

    /**
     * Enter key handler: check the answer, or move on when the question is done.
     */
    public function submit()
    {
        $this->questionDone ? $this->getNewConjugation() : $this->checkAnswer();
    }

    public function getNewConjugation()
    {
        // Disallow skipping: a question must be finished before moving on
        if ($this->roundFinished || ($this->currentConjugation && ! $this->questionDone)) {
            return;
        }

        if ($this->questionNumber >= $this->roundSize) {
            $this->roundFinished = true;
            $this->dispatch('round-finished', score: $this->roundCorrect, total: $this->roundSize);
            return;
        }

        $this->questionNumber++;
        $this->loadQuestion();
    }

    public function restartRound()
    {
        $this->questionNumber = 1;
        $this->roundCorrect = 0;
        $this->roundXp = 0;
        $this->roundFinished = false;
        $this->seenIds = [];
        $this->combo = 0;
        $this->bestCombo = 0;

        $this->loadQuestion();
    }

    protected function loadQuestion(): void
    {
        $this->studentAnswer = '';
        $this->feedback = '';
        $this->correctForm = '';
        $this->lastXp = 0;
        $this->questionDone = false;
        $this->revealed = false;
        $this->remainingTries = $this->maxTries;

        // Prefer a conjugation not yet seen this round; small sets may repeat
        $this->currentConjugation = $this->conjugationsQuery()->whereNotIn('id', $this->seenIds)->inRandomOrder()->first()
            ?? $this->conjugationsQuery()->inRandomOrder()->first();

        if (! $this->currentConjugation) {
            $this->feedback = 'empty';
            return;
        }

        $this->seenIds[] = $this->currentConjugation->id;
        $this->infinitive = $this->currentConjugation->verb->infinitive;
        $this->tense = $this->currentConjugation->tense_id;
        $this->person = $this->currentConjugation->person;

        $this->dispatch('refocus-answer');
    }

    protected function conjugationsQuery()
    {
        $set = $this->conjugationSetId ? ConjugationSet::find($this->conjugationSetId) : null;

        return ($set ? $set->conjugationsQuery() : Conjugation::query()->where('enabled', true))
            ->with(['verb', 'tense']);
    }

    public function checkAnswer()
    {
        if (! $this->currentConjugation || $this->questionDone) {
            return;
        }

        // Do not consume a try if no answer was provided (also supports Enter key)
        if (trim($this->studentAnswer) === '') {
            return;
        }

        $given = $this->normalize($this->studentAnswer);
        $expected = $this->normalize($this->currentConjugation->conjugated_form);
        $isCorrect = $given === $expected;

        // The answer was revealed: retyping it finishes the question but is not recorded
        if ($this->revealed) {
            if ($isCorrect) {
                $this->feedback = 'copied';
                $this->questionDone = true;
            } else {
                $this->studentAnswer = '';
                $this->dispatch('answer-wrong');
                $this->dispatch('refocus-answer');
            }
            return;
        }

        $attempt = $this->maxTries - $this->remainingTries + 1;
        $xp = 0;
        if ($isCorrect) {
            $xp = (int) config($attempt === 1 ? 'practice.xp_first_try' : 'practice.xp_retry');
        }

        if (Auth::check()) {
            StudentAnswer::create([
                'user_id' => Auth::id(),
                'conjugation_id' => $this->currentConjugation->id,
                'student_answer' => $this->studentAnswer,
                'is_correct' => $isCorrect,
                'xp' => $xp,
                'attempt_count' => $attempt,
                'last_practiced_at' => now(),
            ]);
        }

        if ($isCorrect) {
            $this->combo = $attempt === 1 ? $this->combo + 1 : 0;
            $this->bestCombo = max($this->bestCombo, $this->combo);
            $this->roundCorrect++;
            $this->roundXp += $xp;
            $this->lastXp = $xp;
            $this->praise = Arr::random(self::PRAISES);
            $this->correctForm = $this->currentConjugation->conjugated_form;
            $this->feedback = 'correct';
            $this->questionDone = true;

            $this->dispatch('answer-correct', xp: $xp, combo: $this->combo);
            if ($user = Auth::user()) {
                $this->dispatch('stats-updated', xp: $user->totalXp(), streak: $user->streak());
            }
            return;
        }

        // Wrong answer flow: decrement tries and decide what to show
        $this->combo = 0;
        $this->remainingTries = max(0, $this->remainingTries - 1);
        $this->studentAnswer = '';

        if ($this->remainingTries > 0) {
            $this->feedback = 'retry';
        } else {
            // Out of tries: reveal the correct answer, the user must type it to continue
            $this->revealed = true;
            $this->correctForm = $this->currentConjugation->conjugated_form;
            $this->feedback = 'reveal';
        }

        $this->dispatch('answer-wrong');
        $this->dispatch('refocus-answer');
    }

    protected function normalize(string $value): string
    {
        // Ignore case and trim outer whitespace. Preserve accents and inner spaces.
        $value = trim($value);
        return mb_strtolower($value, 'UTF-8');
    }

    public function getPersonPronoun()
    {
        return Conjugation::PRONOUNS[$this->person] ?? $this->person;
    }

    public function getTenseName()
    {
        // The relation is always eager-loaded; no fallback mapping needed
        return $this->currentConjugation?->tense?->name ?? '';
    }

    public function render()
    {
        return view('livewire.conjugation-practice');
    }
}
