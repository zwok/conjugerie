<?php

namespace Database\Seeders;

use App\Models\ConjugationSet;
use App\Models\Verb;
use App\Models\Tense;
use Illuminate\Database\Seeder;

class ConjugationSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Available verbs in database: aller, avoir, connaître, devoir, faire, lire, mettre, parler,
        // permettre, prendre, promettre, prévoir, relire, remettre, revoir, savoir, voir, écrire, être

        // Set 1: Présent - Verbes essentiels
        $set1 = ConjugationSet::create([
            'name' => 'Présent - Verbes essentiels',
            'description' => 'Pratique des verbes les plus courants au présent de l\'indicatif',
        ]);

        $essentialVerbs = Verb::whereIn('infinitive', [
            'être', 'avoir', 'aller', 'faire', 'parler', 'voir', 'savoir'
        ])->pluck('id');

        $set1->verbs()->attach($essentialVerbs);
        $set1->tenses()->attach(['present']);

        // Set 2: Présent - Verbes de perception et communication
        $set2 = ConjugationSet::create([
            'name' => 'Présent - Verbes de perception et communication',
            'description' => 'Pratique des verbes liés à la perception et la communication',
        ]);

        $perceptionVerbs = Verb::whereIn('infinitive', [
            'voir', 'revoir', 'lire', 'relire', 'écrire', 'parler', 'connaître', 'savoir'
        ])->pluck('id');

        $set2->verbs()->attach($perceptionVerbs);
        $set2->tenses()->attach(['present']);

        // Set 3: Présent - Verbes d'action
        $set3 = ConjugationSet::create([
            'name' => 'Présent - Verbes d\'action',
            'description' => 'Pratique des verbes d\'action courants au présent',
        ]);

        $actionVerbs = Verb::whereIn('infinitive', [
            'faire', 'prendre', 'mettre', 'remettre', 'permettre', 'prévoir'
        ])->pluck('id');

        $set3->verbs()->attach($actionVerbs);
        $set3->tenses()->attach(['present']);

        // Set 4: Passé composé - Verbes courants
        $set4 = ConjugationSet::create([
            'name' => 'Passé composé - Verbes courants',
            'description' => 'Pratique du passé composé avec les verbes les plus utilisés',
        ]);

        $passeComposeVerbs = Verb::whereIn('infinitive', [
            'être', 'avoir', 'aller', 'faire', 'voir', 'prendre', 'mettre', 'parler', 'lire', 'écrire'
        ])->pluck('id');

        $set4->verbs()->attach($passeComposeVerbs);
        $set4->tenses()->attach(['passe_compose']);

        // Set 5: Imparfait - Tous les groupes
        $set5 = ConjugationSet::create([
            'name' => 'Imparfait - Verbes variés',
            'description' => 'Pratique de l\'imparfait avec des verbes variés',
        ]);

        $imparfaitVerbs = Verb::whereIn('infinitive', [
            'être', 'avoir', 'faire', 'aller', 'parler', 'voir', 'savoir', 'devoir'
        ])->pluck('id');

        $set5->verbs()->attach($imparfaitVerbs);
        $set5->tenses()->attach(['imparfait']);

        // Set 6: Futur simple - Introduction
        $set6 = ConjugationSet::create([
            'name' => 'Futur simple - Introduction',
            'description' => 'Introduction au futur simple avec des verbes courants',
        ]);

        $futureVerbs = Verb::whereIn('infinitive', [
            'être', 'avoir', 'aller', 'faire', 'parler', 'voir', 'savoir', 'devoir'
        ])->pluck('id');

        $set6->verbs()->attach($futureVerbs);
        $set6->tenses()->attach(['futur_simple']);

        // Set 7: Verbes de promesse et permission
        $set7 = ConjugationSet::create([
            'name' => 'Présent - Verbes de promesse et permission',
            'description' => 'Pratique des verbes promettre, permettre et leurs composés',
        ]);

        $promiseVerbs = Verb::whereIn('infinitive', [
            'promettre', 'permettre', 'mettre', 'remettre', 'devoir', 'prévoir'
        ])->pluck('id');

        $set7->verbs()->attach($promiseVerbs);
        $set7->tenses()->attach(['present']);

        // Set 8: Révision - Lecture et écriture
        $set8 = ConjugationSet::create([
            'name' => 'Révision - Verbes de lecture et écriture',
            'description' => 'Pratique des verbes lire, écrire et leurs composés dans plusieurs temps',
        ]);

        $readWriteVerbs = Verb::whereIn('infinitive', [
            'lire', 'relire', 'écrire', 'voir', 'revoir', 'connaître'
        ])->pluck('id');

        $set8->verbs()->attach($readWriteVerbs);
        $set8->tenses()->attach(['present', 'passe_compose', 'imparfait']);

        // Set 9: Temps composés - Niveau avancé
        $set9 = ConjugationSet::create([
            'name' => 'Plus-que-parfait - Verbes courants',
            'description' => 'Pratique du plus-que-parfait',
        ]);

        $plusQueParfaitVerbs = Verb::whereIn('infinitive', [
            'être', 'avoir', 'faire', 'aller', 'voir', 'savoir', 'prendre', 'mettre'
        ])->pluck('id');

        $set9->verbs()->attach($plusQueParfaitVerbs);
        $set9->tenses()->attach(['plus_que_parfait']);

        // Set 10: Révision complète - Tous niveaux
        $set10 = ConjugationSet::create([
            'name' => 'Révision complète - Verbes essentiels',
            'description' => 'Révision des temps principaux avec les verbes essentiels (présent, passé composé, imparfait, futur)',
        ]);

        $revisionVerbs = Verb::whereIn('infinitive', [
            'être', 'avoir', 'aller', 'faire', 'parler', 'voir', 'prendre', 'mettre', 'devoir', 'savoir'
        ])->pluck('id');

        $set10->verbs()->attach($revisionVerbs);
        $set10->tenses()->attach(['present', 'passe_compose', 'imparfait', 'futur_simple']);
    }
}
