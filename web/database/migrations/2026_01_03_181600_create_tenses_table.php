<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        // Seed canonical tenses
        $tenses = [
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

        foreach ($tenses as $id => $name) {
            DB::table('tenses')->insert([
                'id' => $id,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenses');
    }
};
