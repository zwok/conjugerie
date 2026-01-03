<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conjugations', function (Blueprint $table) {
            $table->string('tense_id')->nullable()->after('verb_id');
        });

        Schema::table('conjugations', function (Blueprint $table) {
            $table->dropColumn('tense');
        });
    }

    public function down(): void
    {
        // Recreate a basic 'tense' column as string and backfill from tense_id, then drop tense_id
        Schema::table('conjugations', function (Blueprint $table) {
            $table->string('tense')->nullable()->after('verb_id');
        });

        Schema::table('conjugations', function (Blueprint $table) {
            $table->dropColumn('tense_id');

        });
    }
};
