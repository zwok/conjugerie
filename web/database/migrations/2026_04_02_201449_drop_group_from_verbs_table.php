<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verbs', function (Blueprint $table) {
            if (Schema::hasColumn('verbs', 'group')) {
                $table->dropColumn('group');
            }
        });
    }

    public function down(): void
    {
        Schema::table('verbs', function (Blueprint $table) {
            if (!Schema::hasColumn('verbs', 'group')) {
                $table->string('group')->nullable()->after('infinitive');
            }
        });
    }
};
