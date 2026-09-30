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
        Schema::create('conjugation_set_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conjugation_set_id')->constrained('conjugation_sets')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate group assignments to the same set
            $table->unique(['conjugation_set_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjugation_set_group');
    }
};
