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
        Schema::table('verbs', function (Blueprint $table) {
            if (Schema::hasColumn('verbs', 'english_translation')) {
                $table->dropColumn('english_translation');
            }
            if (Schema::hasColumn('verbs', 'is_irregular')) {
                $table->dropColumn('is_irregular');
            }
            if (Schema::hasColumn('verbs', 'is_auxiliary')) {
                $table->dropColumn('is_auxiliary');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verbs', function (Blueprint $table) {
            if (!Schema::hasColumn('verbs', 'english_translation')) {
                $table->string('english_translation')->nullable()->after('infinitive');
            }
            if (!Schema::hasColumn('verbs', 'is_irregular')) {
                $table->boolean('is_irregular')->default(false)->after('group');
            }
            if (!Schema::hasColumn('verbs', 'is_auxiliary')) {
                $table->boolean('is_auxiliary')->default(false)->after('is_irregular');
            }
        });
    }
};
