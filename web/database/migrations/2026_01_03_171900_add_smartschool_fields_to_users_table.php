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
        Schema::table('users', function (Blueprint $table) {
            // Smartschool related fields
            $table->string('smartschool_id')->nullable()->after('password');
            $table->string('smartschool_username')->nullable()->after('smartschool_id');
            $table->string('smartschool_platform')->nullable()->after('smartschool_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['smartschool_id', 'smartschool_username', 'smartschool_platform']);
        });
    }
};
