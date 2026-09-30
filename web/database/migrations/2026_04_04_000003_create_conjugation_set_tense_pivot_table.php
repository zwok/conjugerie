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
        Schema::create('conjugation_set_tense', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conjugation_set_id')->constrained('conjugation_sets')->onDelete('cascade');
            $table->string('tense_id');
            $table->foreign('tense_id')->references('id')->on('tenses')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate tense assignments to the same set
            $table->unique(['conjugation_set_id', 'tense_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjugation_set_tense');
    }
};
