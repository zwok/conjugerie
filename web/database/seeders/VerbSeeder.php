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
        $verbs = [
            'aimer', 'parler', 'manger', 'donner', 'travailler',
            'écouter', 'regarder', 'chercher', 'demander', 'jouer',
            'finir', 'choisir', 'réfléchir', 'réussir', 'grandir',
            'être', 'avoir', 'aller', 'faire', 'dire',
            'venir', 'voir', 'savoir', 'pouvoir', 'vouloir',
            'prendre', 'mettre', 'lire', 'écrire', 'boire',
        ];

        foreach ($verbs as $infinitive) {
            Verb::create(['infinitive' => $infinitive]);
        }
    }
}
