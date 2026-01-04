<?php

namespace App\Livewire;

use App\Models\Conjugation;
use App\Models\StudentAnswer;
use App\Models\Verb;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConjugationPractice extends Component
{
    public $currentConjugation = null;
    public $studentAnswer = '';
    public $message = '';
    public $messageType = '';
    public $showFeedback = false;
    public $infinitive = '';
    public $tense = '';
    public $person = '';

    // Attempts state
    public int $maxTries = 2;
    public int $remainingTries = 2;
    public bool $questionDone = false; // becomes true after correct answer or after tries depleted

    // Map of person pronouns for display
    protected $personPronouns = [
        'je' => 'Je',
        'tu' => 'Tu',
        'il_elle_on' => 'Il/Elle/On',
        'nous' => 'Nous',
        'vous' => 'Vous',
        'ils_elles' => 'Ils/Elles'
    ];


    public function mount()
    {
        // Load configurable max attempts
        $this->maxTries = (int) config('practice.max_attempts', 2);
        $this->remainingTries = $this->maxTries;
        $this->getNewConjugation();
    }

    public function getNewConjugation()
    {
        // Disallow skipping: if a question is active and not finished, do nothing
        if ($this->currentConjugation && !$this->questionDone) {
            $this->message = 'Veuillez terminer cette question avant de continuer. Utilisez toutes les tentatives ou répondez correctement.';
            $this->messageType = 'error';
            $this->showFeedback = true;
            return;
        }

        // Reset state
        $this->studentAnswer = '';
        $this->message = '';
        $this->messageType = '';
        $this->showFeedback = false;
        $this->questionDone = false;
        $this->remainingTries = $this->maxTries;

        // Get a random conjugation
        // In a real app, you might want to select based on the user's progress
        $this->currentConjugation = Conjugation::with(['verb','tense'])
            ->where('enabled', true)
            ->inRandomOrder()
            ->first();

        if ($this->currentConjugation) {
            $this->infinitive = $this->currentConjugation->verb->infinitive;
            $this->tense = $this->currentConjugation->tense_id;
            $this->person = $this->currentConjugation->person;
        } else {
            // Handle case where no conjugations exist yet
            $this->message = 'Aucune conjugaison disponible. Veuillez remplir la base de données.';
            $this->messageType = 'error';
            $this->showFeedback = true;
        }
    }

    public function checkAnswer()
    {
        if (!$this->currentConjugation || $this->questionDone) {
            return;
        }

        // Do not consume a try if no answer was provided (also supports Enter key)
        if (trim($this->studentAnswer) === '') {
            return;
        }

        $given = $this->normalize($this->studentAnswer);
        $expected = $this->normalize($this->currentConjugation->conjugated_form);
        $isCorrect = $given !== '' && $given === $expected;

        // Save the student's answer
        if (Auth::check()) {
            StudentAnswer::create([
                'user_id' => Auth::id(),
                'conjugation_id' => $this->currentConjugation->id,
                'student_answer' => $this->studentAnswer,
                'is_correct' => $isCorrect,
                'last_practiced_at' => now(),
            ]);
        }

        if ($isCorrect) {
            $this->message = 'Correct ! La réponse est : ' . $this->currentConjugation->conjugated_form;
            $this->messageType = 'success';
            $this->questionDone = true;
            $this->showFeedback = true;
            // Make Next button appear by indicating no tries left
            $this->remainingTries = 0;
            return;
        }

        // Wrong answer flow: decrement tries and decide what to show
        $this->remainingTries = max(0, $this->remainingTries - 1);

        if ($this->remainingTries > 0) {
            $triesWord = $this->remainingTries === 1 ? 'tentative' : 'tentatives';
            $this->message = 'Incorrect. Veuillez réessayer. (' . $this->remainingTries . ' ' . $triesWord . ' restantes)';
            $this->messageType = 'error';
            $this->showFeedback = true;
            // Clear the input so the user re-enters their next attempt
            $this->studentAnswer = '';
            // Ask the browser to refocus the input
            $this->dispatch('refocus-answer');
        } else {
            // Out of tries: reveal correct answer and finish question
            $this->message = 'Plus de tentatives. La bonne réponse est : <strong>' . $this->currentConjugation->conjugated_form . '</strong>';
            $this->messageType = 'error';
            $this->showFeedback = true;
            // Clear input before disabling field to ensure DOM is updated
            $this->studentAnswer = '';
            $this->questionDone = true;
        }
    }

    protected function normalize(string $value): string
    {
        // Ignore case and trim outer whitespace. Preserve accents and inner spaces.
        $value = trim($value);
        return mb_strtolower($value, 'UTF-8');
    }

    public function getPersonPronoun()
    {
        return $this->personPronouns[$this->person] ?? $this->person;
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
