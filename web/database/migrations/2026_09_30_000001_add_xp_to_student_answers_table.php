<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->unsignedSmallInteger('xp')->default(0)->after('is_correct');
        });

        // Existing correct answers get the base XP so current rankings carry over
        DB::table('student_answers')
            ->where('is_correct', true)
            ->update(['xp' => (int) config('practice.xp_retry', 10)]);
    }

    public function down(): void
    {
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropColumn('xp');
        });
    }
};
