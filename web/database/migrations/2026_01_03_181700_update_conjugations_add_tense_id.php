<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add new tense_id column (nullable for transition)
        Schema::table('conjugations', function (Blueprint $table) {
            $table->string('tense_id')->nullable()->after('verb_id');
        });

        // 2) Seed mapping from old enum values (with possible accents) to new canonical IDs
        $map = [
            'present' => 'present',
            'imparfait' => 'imparfait',
            'futur_simple' => 'futur_simple',
            'plus_que_parfait' => 'plus_que_parfait',

            // Passé composé variants
            'passé_composé' => 'passe_compose',
            'passe_compose' => 'passe_compose',

            // Futur antérieur variants
            'futur_antérieur' => 'futur_anterieur',
            'futur_anterieur' => 'futur_anterieur',

            // Conditionnel variants
            'conditionnel_présent' => 'conditionnel_present',
            'conditionnel_present' => 'conditionnel_present',
            'conditionnel_passé' => 'conditionnel_passe',
            'conditionnel_passe' => 'conditionnel_passe',
        ];

        // 3) Delete rows for passé_simple (not in the new canonical list)
        DB::table('conjugations')->where('tense', 'passé_simple')->delete();

        // 4) Backfill tense_id based on mapping
        DB::table('conjugations')->orderBy('id')->chunkById(100, function ($rows) use ($map) {
            foreach ($rows as $row) {
                $old = $row->tense;
                $newId = $map[$old] ?? null;
                if ($newId) {
                    DB::table('conjugations')->where('id', $row->id)->update(['tense_id' => $newId]);
                }
            }
        });

        // 5) Add FK and new unique index; drop old unique
        Schema::table('conjugations', function (Blueprint $table) {
            // Drop old unique on (verb_id, tense, person)
            try {
                $table->dropUnique('conjugations_verb_id_tense_person_unique');
            } catch (Throwable $e) {
                // ignore if it doesn't exist
            }

            $table->foreign('tense_id')->references('id')->on('tenses')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['verb_id', 'tense_id', 'person'], 'conjugations_verb_id_tense_id_person_unique');
        });

        // 6) Finally, drop the old column 'tense'
        Schema::table('conjugations', function (Blueprint $table) {
            $table->dropColumn('tense');
        });

        // 7) Optionally make tense_id not nullable (requires doctrine/dbal to change column type)
        // If DBAL is not installed, this step will be skipped safely.
        try {
            Schema::table('conjugations', function (Blueprint $table) {
                $table->string('tense_id')->nullable(false)->change();
            });
        } catch (Throwable $e) {
            // skip making not-null if change() isn't supported
        }
    }

    public function down(): void
    {
        // Recreate a basic 'tense' column as string and backfill from tense_id, then drop tense_id
        Schema::table('conjugations', function (Blueprint $table) {
            $table->string('tense')->nullable()->after('verb_id');
        });

        // Reverse mapping (use canonical ids to a sensible string without accents)
        $rev = [
            'present' => 'present',
            'imparfait' => 'imparfait',
            'futur_simple' => 'futur_simple',
            'passe_compose' => 'passe_compose',
            'plus_que_parfait' => 'plus_que_parfait',
            'futur_anterieur' => 'futur_anterieur',
            'conditionnel_present' => 'conditionnel_present',
            'conditionnel_passe' => 'conditionnel_passe',
        ];

        DB::table('conjugations')->orderBy('id')->chunkById(100, function ($rows) use ($rev) {
            foreach ($rows as $row) {
                $new = $rev[$row->tense_id] ?? null;
                if ($new) {
                    DB::table('conjugations')->where('id', $row->id)->update(['tense' => $new]);
                }
            }
        });

        Schema::table('conjugations', function (Blueprint $table) {
            try {
                $table->dropUnique(['verb_id', 'tense_id', 'person']);
            } catch (Throwable $e) {
                // ignore
            }
            try {
                $table->dropForeign(['tense_id']);
            } catch (Throwable $e) {
                // ignore
            }
            $table->dropColumn('tense_id');

            // Restore unique on (verb_id, tense, person)
            $table->unique(['verb_id', 'tense', 'person']);
        });
    }
};
