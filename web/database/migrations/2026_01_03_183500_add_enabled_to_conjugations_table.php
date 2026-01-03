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
        Schema::table('conjugations', function (Blueprint $table) {
            $table->boolean('enabled')->default(true)->after('conjugated_form');
            $table->index('enabled');
        });

        // Backfill existing rows to enabled = true explicitly (in case default isn't applied)
        \DB::table('conjugations')->update(['enabled' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conjugations', function (Blueprint $table) {
            $table->dropIndex(['enabled']);
            $table->dropColumn('enabled');
        });
    }
};
