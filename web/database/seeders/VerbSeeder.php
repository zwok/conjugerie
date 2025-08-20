<?php

namespace Database\Seeders;

use App\Models\Verb;
use Illuminate\Database\Seeder;

class VerbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common first group (-er) verbs
        $firstGroupVerbs = [
            ['infinitive' => 'aimer', 'english_translation' => 'to love', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'parler', 'english_translation' => 'to speak', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'manger', 'english_translation' => 'to eat', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'donner', 'english_translation' => 'to give', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'travailler', 'english_translation' => 'to work', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'écouter', 'english_translation' => 'to listen', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'regarder', 'english_translation' => 'to watch', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'chercher', 'english_translation' => 'to search', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'demander', 'english_translation' => 'to ask', 'group' => 'first', 'is_irregular' => false],
            ['infinitive' => 'jouer', 'english_translation' => 'to play', 'group' => 'first', 'is_irregular' => false],
        ];

        // Common second group (-ir) verbs
        $secondGroupVerbs = [
            ['infinitive' => 'finir', 'english_translation' => 'to finish', 'group' => 'second', 'is_irregular' => false],
            ['infinitive' => 'choisir', 'english_translation' => 'to choose', 'group' => 'second', 'is_irregular' => false],
            ['infinitive' => 'réfléchir', 'english_translation' => 'to think', 'group' => 'second', 'is_irregular' => false],
            ['infinitive' => 'réussir', 'english_translation' => 'to succeed', 'group' => 'second', 'is_irregular' => false],
            ['infinitive' => 'grandir', 'english_translation' => 'to grow', 'group' => 'second', 'is_irregular' => false],
        ];

        // Common third group verbs (irregular and -re verbs)
        $thirdGroupVerbs = [
            ['infinitive' => 'être', 'english_translation' => 'to be', 'group' => 'third', 'is_irregular' => true, 'is_auxiliary' => true],
            ['infinitive' => 'avoir', 'english_translation' => 'to have', 'group' => 'third', 'is_irregular' => true, 'is_auxiliary' => true],
            ['infinitive' => 'aller', 'english_translation' => 'to go', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'faire', 'english_translation' => 'to do/make', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'dire', 'english_translation' => 'to say', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'venir', 'english_translation' => 'to come', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'voir', 'english_translation' => 'to see', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'savoir', 'english_translation' => 'to know', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'pouvoir', 'english_translation' => 'to be able to', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'vouloir', 'english_translation' => 'to want', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'prendre', 'english_translation' => 'to take', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'mettre', 'english_translation' => 'to put', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'lire', 'english_translation' => 'to read', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'écrire', 'english_translation' => 'to write', 'group' => 'third', 'is_irregular' => true],
            ['infinitive' => 'boire', 'english_translation' => 'to drink', 'group' => 'third', 'is_irregular' => true],
        ];

        // Combine all verbs
        $allVerbs = array_merge($firstGroupVerbs, $secondGroupVerbs, $thirdGroupVerbs);

        // Insert verbs into the database
        foreach ($allVerbs as $verb) {
            Verb::create($verb);
        }
    }
}
