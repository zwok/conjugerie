<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call the verb seeder first to create verbs
        $this->call(VerbSeeder::class);

        // Then call the conjugation seeder to create conjugations for those verbs
        $this->call(ConjugationSeeder::class);
    }
}
