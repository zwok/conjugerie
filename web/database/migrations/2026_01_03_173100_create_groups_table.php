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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            // External identifier coming from Smartschool API (maps to "groupID")
            $table->string('external_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->nullable();
            $table->string('platform'); // e.g. https://tsm.smartschool.be
            $table->timestamps();

            // Ensure uniqueness of the external group within a platform
            $table->unique(['external_id', 'platform']);
            // Helpful indexes for lookups
            $table->index('name');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
