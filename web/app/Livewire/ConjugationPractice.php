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

    // Map of tense names for display
    protected $tenseNames = [
        'present' => 'Présent',
        'imparfait' => 'Imparfait',
        'futur_simple' => 'Futur Simple',
        'passé_composé' => 'Passé Composé',
        'plus_que_parfait' => 'Plus-que-parfait',
        'passé_simple' => 'Passé Simple',
        'futur_antérieur' => 'Futur Antérieur',
        'conditionnel_présent' => 'Conditionnel Présent',
        'conditionnel_passé' => 'Conditionnel Passé'
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
        $this->currentConjugation = Conjugation::with('verb')
            ->inRandomOrder()
            ->first();

        if ($this->currentConjugation) {
            $this->infinitive = $this->currentConjugation->verb->infinitive;
            $this->tense = $this->currentConjugation->tense;
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
        return $this->tenseNames[$this->tense] ?? $this->tense;
    }

    public function render()
    {
        return view('livewire.conjugation-practice');
    }
}
