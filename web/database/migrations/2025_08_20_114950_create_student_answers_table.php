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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('conjugation_id')->constrained()->onDelete('cascade');
            $table->string('student_answer');
            $table->boolean('is_correct');
            $table->integer('attempt_count')->default(1);
            $table->timestamp('last_practiced_at');
            $table->timestamps();

            // Index for performance when querying user progress
            $table->index(['user_id', 'is_correct']);
            $table->index(['user_id', 'last_practiced_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
