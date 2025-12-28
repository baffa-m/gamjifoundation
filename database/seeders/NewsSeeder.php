<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 news articles
        // Create a user for the author if none exists, or pick one
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

        \App\Models\News::factory(10)->create([
            'author_id' => $user->id,
        ]);
    }
}
