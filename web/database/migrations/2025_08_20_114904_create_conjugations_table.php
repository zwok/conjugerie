<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conjugations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verb_id')->constrained()->onDelete('cascade');
            $table->enum('tense', [
                'present', 'imparfait', 'futur_simple', 'passé_composé',
                'plus_que_parfait', 'passé_simple', 'futur_antérieur',
                'conditionnel_présent', 'conditionnel_passé'
            ]);
            $table->enum('person', ['je', 'tu', 'il_elle_on', 'nous', 'vous', 'ils_elles']);
            $table->string('conjugated_form');
            $table->unique(['verb_id', 'tense', 'person']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjugations');
    }
};
