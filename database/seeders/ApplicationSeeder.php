<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applicants = \App\Models\Applicant::all();
        $awards = \App\Models\Award::all();

        if ($awards->isEmpty()) {
            return;
        }

        foreach ($applicants as $applicant) {
            // Each applicant applies to 1-2 random awards
            $randomAwards = $awards->random(rand(1, 2));

            foreach ($randomAwards as $award) {
                \App\Models\Application::factory()->create([
                    'applicant_id' => $applicant->id,
                    'award_id' => $award->id,
                ]);
            }
        }
    }
}
