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
            ['infinitive' => 'aimer', 'group' => 'first'],
            ['infinitive' => 'parler', 'group' => 'first'],
            ['infinitive' => 'manger', 'group' => 'first'],
            ['infinitive' => 'donner', 'group' => 'first'],
            ['infinitive' => 'travailler', 'group' => 'first'],
            ['infinitive' => 'écouter', 'group' => 'first'],
            ['infinitive' => 'regarder', 'group' => 'first'],
            ['infinitive' => 'chercher', 'group' => 'first'],
            ['infinitive' => 'demander', 'group' => 'first'],
            ['infinitive' => 'jouer', 'group' => 'first'],
        ];

        // Common second group (-ir) verbs
        $secondGroupVerbs = [
            ['infinitive' => 'finir', 'group' => 'second'],
            ['infinitive' => 'choisir', 'group' => 'second'],
            ['infinitive' => 'réfléchir', 'group' => 'second'],
            ['infinitive' => 'réussir', 'group' => 'second'],
            ['infinitive' => 'grandir', 'group' => 'second'],
        ];

        // Common third group verbs (selected examples)
        $thirdGroupVerbs = [
            ['infinitive' => 'être', 'group' => 'third'],
            ['infinitive' => 'avoir', 'group' => 'third'],
            ['infinitive' => 'aller', 'group' => 'third'],
            ['infinitive' => 'faire', 'group' => 'third'],
            ['infinitive' => 'dire', 'group' => 'third'],
            ['infinitive' => 'venir', 'group' => 'third'],
            ['infinitive' => 'voir', 'group' => 'third'],
            ['infinitive' => 'savoir', 'group' => 'third'],
            ['infinitive' => 'pouvoir', 'group' => 'third'],
            ['infinitive' => 'vouloir', 'group' => 'third'],
            ['infinitive' => 'prendre', 'group' => 'third'],
            ['infinitive' => 'mettre', 'group' => 'third'],
            ['infinitive' => 'lire', 'group' => 'third'],
            ['infinitive' => 'écrire', 'group' => 'third'],
            ['infinitive' => 'boire', 'group' => 'third'],
        ];

        // Combine all verbs
        $allVerbs = array_merge($firstGroupVerbs, $secondGroupVerbs, $thirdGroupVerbs);

        // Insert verbs into the database
        foreach ($allVerbs as $verb) {
            Verb::create($verb);
        }
    }
}
