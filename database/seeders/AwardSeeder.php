<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all sponsors
        $sponsors = \App\Models\Sponsor::all();

        // Create 2-3 awards for each sponsor
        foreach ($sponsors as $sponsor) {
            \App\Models\Award::factory(rand(2, 3))->create([
                'sponsor_id' => $sponsor->id,
            ]);
        }
    }
}
