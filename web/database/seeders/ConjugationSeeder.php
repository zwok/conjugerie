<?php

namespace Database\Seeders;

use App\Models\Conjugation;
use App\Models\Verb;
use Illuminate\Database\Seeder;

class ConjugationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all verbs from the database
        $verbs = Verb::all();

        foreach ($verbs as $verb) {
            $this->seedConjugationsForVerb($verb);
        }
    }

    /**
     * Seed conjugations for a specific verb
     */
    private function seedConjugationsForVerb(Verb $verb): void
    {
        // Generate conjugations based on verb group and irregularity
        if ($verb->infinitive === 'être') {
            $this->seedEtreConjugations($verb);
        } elseif ($verb->infinitive === 'avoir') {
            $this->seedAvoirConjugations($verb);
        } elseif ($verb->infinitive === 'aller') {
            $this->seedAllerConjugations($verb);
        } elseif ($verb->infinitive === 'faire') {
            $this->seedFaireConjugations($verb);
        } elseif ($verb->group === 'first') {
            $this->seedFirstGroupConjugations($verb);
        } elseif ($verb->group === 'second') {
            $this->seedSecondGroupConjugations($verb);
        } else {
            // For other third group verbs, we'll use a simplified approach
            // In a real application, you would want to handle each irregular verb specifically
            $this->seedSimplifiedThirdGroupConjugations($verb);
        }
    }

    /**
     * Seed conjugations for the verb "être"
     */
    private function seedEtreConjugations(Verb $verb): void
    {
        // Present tense conjugations
        $presentConjugations = [
            'je' => 'suis',
            'tu' => 'es',
            'il_elle_on' => 'est',
            'nous' => 'sommes',
            'vous' => 'êtes',
            'ils_elles' => 'sont'
        ];

        // Imparfait tense conjugations
        $imparfaitConjugations = [
            'je' => 'étais',
            'tu' => 'étais',
            'il_elle_on' => 'était',
            'nous' => 'étions',
            'vous' => 'étiez',
            'ils_elles' => 'étaient'
        ];

        // Futur simple tense conjugations
        $futurSimpleConjugations = [
            'je' => 'serai',
            'tu' => 'seras',
            'il_elle_on' => 'sera',
            'nous' => 'serons',
            'vous' => 'serez',
            'ils_elles' => 'seront'
        ];

        // Passé composé tense conjugations
        $passeComposeConjugations = [
            'je' => 'ai été',
            'tu' => 'as été',
            'il_elle_on' => 'a été',
            'nous' => 'avons été',
            'vous' => 'avez été',
            'ils_elles' => 'ont été'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Seed conjugations for the verb "avoir"
     */
    private function seedAvoirConjugations(Verb $verb): void
    {
        // Present tense conjugations
        $presentConjugations = [
            'je' => 'ai',
            'tu' => 'as',
            'il_elle_on' => 'a',
            'nous' => 'avons',
            'vous' => 'avez',
            'ils_elles' => 'ont'
        ];

        // Imparfait tense conjugations
        $imparfaitConjugations = [
            'je' => 'avais',
            'tu' => 'avais',
            'il_elle_on' => 'avait',
            'nous' => 'avions',
            'vous' => 'aviez',
            'ils_elles' => 'avaient'
        ];

        // Futur simple tense conjugations
        $futurSimpleConjugations = [
            'je' => 'aurai',
            'tu' => 'auras',
            'il_elle_on' => 'aura',
            'nous' => 'aurons',
            'vous' => 'aurez',
            'ils_elles' => 'auront'
        ];

        // Passé composé tense conjugations
        $passeComposeConjugations = [
            'je' => 'ai eu',
            'tu' => 'as eu',
            'il_elle_on' => 'a eu',
            'nous' => 'avons eu',
            'vous' => 'avez eu',
            'ils_elles' => 'ont eu'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Seed conjugations for the verb "aller"
     */
    private function seedAllerConjugations(Verb $verb): void
    {
        // Present tense conjugations
        $presentConjugations = [
            'je' => 'vais',
            'tu' => 'vas',
            'il_elle_on' => 'va',
            'nous' => 'allons',
            'vous' => 'allez',
            'ils_elles' => 'vont'
        ];

        // Imparfait tense conjugations
        $imparfaitConjugations = [
            'je' => 'allais',
            'tu' => 'allais',
            'il_elle_on' => 'allait',
            'nous' => 'allions',
            'vous' => 'alliez',
            'ils_elles' => 'allaient'
        ];

        // Futur simple tense conjugations
        $futurSimpleConjugations = [
            'je' => 'irai',
            'tu' => 'iras',
            'il_elle_on' => 'ira',
            'nous' => 'irons',
            'vous' => 'irez',
            'ils_elles' => 'iront'
        ];

        // Passé composé tense conjugations
        $passeComposeConjugations = [
            'je' => 'suis allé',
            'tu' => 'es allé',
            'il_elle_on' => 'est allé',
            'nous' => 'sommes allés',
            'vous' => 'êtes allés',
            'ils_elles' => 'sont allés'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Seed conjugations for the verb "faire"
     */
    private function seedFaireConjugations(Verb $verb): void
    {
        // Present tense conjugations
        $presentConjugations = [
            'je' => 'fais',
            'tu' => 'fais',
            'il_elle_on' => 'fait',
            'nous' => 'faisons',
            'vous' => 'faites',
            'ils_elles' => 'font'
        ];

        // Imparfait tense conjugations
        $imparfaitConjugations = [
            'je' => 'faisais',
            'tu' => 'faisais',
            'il_elle_on' => 'faisait',
            'nous' => 'faisions',
            'vous' => 'faisiez',
            'ils_elles' => 'faisaient'
        ];

        // Futur simple tense conjugations
        $futurSimpleConjugations = [
            'je' => 'ferai',
            'tu' => 'feras',
            'il_elle_on' => 'fera',
            'nous' => 'ferons',
            'vous' => 'ferez',
            'ils_elles' => 'feront'
        ];

        // Passé composé tense conjugations
        $passeComposeConjugations = [
            'je' => 'ai fait',
            'tu' => 'as fait',
            'il_elle_on' => 'a fait',
            'nous' => 'avons fait',
            'vous' => 'avez fait',
            'ils_elles' => 'ont fait'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Seed conjugations for first group verbs (-er)
     */
    private function seedFirstGroupConjugations(Verb $verb): void
    {
        $stem = substr($verb->infinitive, 0, -2); // Remove -er

        // Present tense conjugations
        $presentConjugations = [
            'je' => $stem . 'e',
            'tu' => $stem . 'es',
            'il_elle_on' => $stem . 'e',
            'nous' => $stem . 'ons',
            'vous' => $stem . 'ez',
            'ils_elles' => $stem . 'ent'
        ];

        // Special case for verbs like "manger" that need a 'e' before 'o' to keep the soft 'g' sound
        if (substr($verb->infinitive, -6) === 'manger') {
            $presentConjugations['nous'] = $stem . 'eons';
        }

        // Imparfait tense conjugations
        $imparfaitConjugations = [
            'je' => $stem . 'ais',
            'tu' => $stem . 'ais',
            'il_elle_on' => $stem . 'ait',
            'nous' => $stem . 'ions',
            'vous' => $stem . 'iez',
            'ils_elles' => $stem . 'aient'
        ];

        // Futur simple tense conjugations
        $futurSimpleConjugations = [
            'je' => $verb->infinitive . 'ai',
            'tu' => $verb->infinitive . 'as',
            'il_elle_on' => $verb->infinitive . 'a',
            'nous' => $verb->infinitive . 'ons',
            'vous' => $verb->infinitive . 'ez',
            'ils_elles' => $verb->infinitive . 'ont'
        ];

        // Passé composé tense conjugations
        $passeComposeConjugations = [
            'je' => 'ai ' . $stem . 'é',
            'tu' => 'as ' . $stem . 'é',
            'il_elle_on' => 'a ' . $stem . 'é',
            'nous' => 'avons ' . $stem . 'é',
            'vous' => 'avez ' . $stem . 'é',
            'ils_elles' => 'ont ' . $stem . 'é'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Seed conjugations for second group verbs (-ir)
     */
    private function seedSecondGroupConjugations(Verb $verb): void
    {
        $stem = substr($verb->infinitive, 0, -2); // Remove -ir

        // Present tense conjugations
        $presentConjugations = [
            'je' => $stem . 'is',
            'tu' => $stem . 'is',
            'il_elle_on' => $stem . 'it',
            'nous' => $stem . 'issons',
            'vous' => $stem . 'issez',
            'ils_elles' => $stem . 'issent'
        ];

        // Imparfait tense conjugations
        $imparfaitConjugations = [
            'je' => $stem . 'issais',
            'tu' => $stem . 'issais',
            'il_elle_on' => $stem . 'issait',
            'nous' => $stem . 'issions',
            'vous' => $stem . 'issiez',
            'ils_elles' => $stem . 'issaient'
        ];

        // Futur simple tense conjugations
        $futurSimpleConjugations = [
            'je' => $verb->infinitive . 'ai',
            'tu' => $verb->infinitive . 'as',
            'il_elle_on' => $verb->infinitive . 'a',
            'nous' => $verb->infinitive . 'ons',
            'vous' => $verb->infinitive . 'ez',
            'ils_elles' => $verb->infinitive . 'ont'
        ];

        // Passé composé tense conjugations
        $passeComposeConjugations = [
            'je' => 'ai ' . $stem . 'i',
            'tu' => 'as ' . $stem . 'i',
            'il_elle_on' => 'a ' . $stem . 'i',
            'nous' => 'avons ' . $stem . 'i',
            'vous' => 'avez ' . $stem . 'i',
            'ils_elles' => 'ont ' . $stem . 'i'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Seed simplified conjugations for third group verbs
     * Note: This is a simplified approach. In a real application, you would want to handle each irregular verb specifically.
     */
    private function seedSimplifiedThirdGroupConjugations(Verb $verb): void
    {
        // For demonstration purposes, we'll use some common patterns
        // but this won't be accurate for all third group verbs

        // For -re verbs
        if (substr($verb->infinitive, -2) === 're') {
            $stem = substr($verb->infinitive, 0, -2); // Remove -re

            // Present tense conjugations (simplified)
            $presentConjugations = [
                'je' => $stem . 's',
                'tu' => $stem . 's',
                'il_elle_on' => $stem,
                'nous' => $stem . 'ons',
                'vous' => $stem . 'ez',
                'ils_elles' => $stem . 'ent'
            ];

            // Passé composé tense conjugations
            $passeComposeConjugations = [
                'je' => 'ai ' . $stem . 'u',
                'tu' => 'as ' . $stem . 'u',
                'il_elle_on' => 'a ' . $stem . 'u',
                'nous' => 'avons ' . $stem . 'u',
                'vous' => 'avez ' . $stem . 'u',
                'ils_elles' => 'ont ' . $stem . 'u'
            ];
        }
        // For -oir verbs
        elseif (substr($verb->infinitive, -3) === 'oir') {
            $stem = substr($verb->infinitive, 0, -3); // Remove -oir

            // Present tense conjugations (simplified)
            $presentConjugations = [
                'je' => $stem . 'ois',
                'tu' => $stem . 'ois',
                'il_elle_on' => $stem . 'oit',
                'nous' => $stem . 'oyons',
                'vous' => $stem . 'oyez',
                'ils_elles' => $stem . 'oient'
            ];

            // Passé composé tense conjugations
            $passeComposeConjugations = [
                'je' => 'ai ' . $stem . 'u',
                'tu' => 'as ' . $stem . 'u',
                'il_elle_on' => 'a ' . $stem . 'u',
                'nous' => 'avons ' . $stem . 'u',
                'vous' => 'avez ' . $stem . 'u',
                'ils_elles' => 'ont ' . $stem . 'u'
            ];
        }
        // For other third group verbs (simplified)
        else {
            $stem = substr($verb->infinitive, 0, -2); // Remove last two characters

            // Present tense conjugations (very simplified)
            $presentConjugations = [
                'je' => $stem . 's',
                'tu' => $stem . 's',
                'il_elle_on' => $stem . 't',
                'nous' => $stem . 'ons',
                'vous' => $stem . 'ez',
                'ils_elles' => $stem . 'ent'
            ];

            // Passé composé tense conjugations
            $passeComposeConjugations = [
                'je' => 'ai ' . $stem . 'u',
                'tu' => 'as ' . $stem . 'u',
                'il_elle_on' => 'a ' . $stem . 'u',
                'nous' => 'avons ' . $stem . 'u',
                'vous' => 'avez ' . $stem . 'u',
                'ils_elles' => 'ont ' . $stem . 'u'
            ];
        }

        // Imparfait tense conjugations (simplified)
        $imparfaitConjugations = [
            'je' => $stem . 'ais',
            'tu' => $stem . 'ais',
            'il_elle_on' => $stem . 'ait',
            'nous' => $stem . 'ions',
            'vous' => $stem . 'iez',
            'ils_elles' => $stem . 'aient'
        ];

        // Futur simple tense conjugations (simplified)
        $futurSimpleConjugations = [
            'je' => $verb->infinitive . 'ai',
            'tu' => $verb->infinitive . 'as',
            'il_elle_on' => $verb->infinitive . 'a',
            'nous' => $verb->infinitive . 'ons',
            'vous' => $verb->infinitive . 'ez',
            'ils_elles' => $verb->infinitive . 'ont'
        ];

        $this->createConjugationsForTense($verb, 'present', $presentConjugations);
        $this->createConjugationsForTense($verb, 'imparfait', $imparfaitConjugations);
        $this->createConjugationsForTense($verb, 'futur_simple', $futurSimpleConjugations);
        $this->createConjugationsForTense($verb, 'passé_composé', $passeComposeConjugations);
    }

    /**
     * Normalize an input tense key (which may contain accents/legacy keys)
     * to the canonical `tense_id` used in the `tenses` table.
     */
    private function normalizeTenseId(string $tense): ?string
    {
        $map = [
            'present' => 'present',
            'imparfait' => 'imparfait',
            'futur_simple' => 'futur_simple',
            'plus_que_parfait' => 'plus_que_parfait',
            'passé_composé' => 'passe_compose',
            'passe_compose' => 'passe_compose',
            'futur_antérieur' => 'futur_anterieur',
            'futur_anterieur' => 'futur_anterieur',
            'conditionnel_présent' => 'conditionnel_present',
            'conditionnel_present' => 'conditionnel_present',
            'conditionnel_passé' => 'conditionnel_passe',
            'conditionnel_passe' => 'conditionnel_passe',
            // New tenses supported by catalog (keep if used later)
            'subjonctif_present' => 'subjonctif_present',
            'subjonctif_passe' => 'subjonctif_passe',
            'imperatif_present' => 'imperatif_present',
        ];

        // Explicitly drop passé_simple as per current requirements
        if ($tense === 'passé_simple' || $tense === 'passe_simple') {
            return null;
        }

        return $map[$tense] ?? null;
    }

    /**
     * Create conjugations for a specific tense (uses `tense_id`).
     */
    private function createConjugationsForTense(Verb $verb, string $tense, array $conjugations): void
    {
        $tenseId = $this->normalizeTenseId($tense);
        if (!$tenseId) {
            // Skip creating if tense is not part of the canonical set
            return;
        }

        foreach ($conjugations as $person => $conjugatedForm) {
            Conjugation::create([
                'verb_id' => $verb->id,
                'tense_id' => $tenseId,
                'person' => $person,
                'conjugated_form' => $conjugatedForm
            ]);
        }
    }
}
