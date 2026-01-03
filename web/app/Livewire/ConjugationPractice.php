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

    // Map of person pronouns for display
    protected $personPronouns = [
        'je' => 'Je',
        'tu' => 'Tu',
        'il_elle_on' => 'Il/Elle/On',
        'nous' => 'Nous',
        'vous' => 'Vous',
        'ils_elles' => 'Ils/Elles'
    ];

    // Canonical map of tense IDs to display names (fallback if relation not loaded)
    protected $tenseNames = [
        'present' => 'Présent',
        'passe_compose' => 'Passé composé',
        'imparfait' => 'Imparfait',
        'plus_que_parfait' => 'Plus-que-parfait',
        'futur_simple' => 'Futur simple',
        'futur_anterieur' => 'Futur antérieur',
        'conditionnel_present' => 'Conditionnel présent',
        'conditionnel_passe' => 'Conditionnel passé',
        'subjonctif_present' => 'Subjonctif présent',
        'subjonctif_passe' => 'Subjonctif passé',
        'imperatif_present' => 'Impératif présent',
    ];

    public function mount()
    {
        $this->getNewConjugation();
    }

    public function getNewConjugation()
    {
        // Reset state
        $this->studentAnswer = '';
        $this->message = '';
        $this->messageType = '';
        $this->showFeedback = false;

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
            $this->message = 'No conjugations available. Please seed the database.';
            $this->messageType = 'error';
            $this->showFeedback = true;
        }
    }

    public function checkAnswer()
    {
        if (!$this->currentConjugation) {
            return;
        }

        $isCorrect = $this->studentAnswer === $this->currentConjugation->conjugated_form;

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

        // Provide feedback
        if ($isCorrect) {
            $this->message = 'Correct! The answer is: ' . $this->currentConjugation->conjugated_form;
            $this->messageType = 'success';
        } else {
            $this->message = 'Incorrect. The correct answer is: ' . $this->currentConjugation->conjugated_form;
            $this->messageType = 'error';
        }

        $this->showFeedback = true;
    }

    public function getPersonPronoun()
    {
        return $this->personPronouns[$this->person] ?? $this->person;
    }

    public function getTenseName()
    {
        if ($this->currentConjugation && $this->currentConjugation->relationLoaded('tense') && $this->currentConjugation->tense) {
            return $this->currentConjugation->tense->name;
        }
        return $this->tenseNames[$this->tense] ?? $this->tense;
    }

    public function render()
    {
        return view('livewire.conjugation-practice');
    }
}
