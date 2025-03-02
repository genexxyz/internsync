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
        // User::factory(10)->create();

        // Create 10 users with instructors
    User::factory(30)->create()->each(function ($user) {
        \App\Models\Instructor::factory()->create([
            'user_id' => $user->id,  // Link the instructor to the user
        ]);
    });
    }
}
