<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Applicant;
use App\Models\Award;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'applicant_id' => Applicant::factory(),
            'award_id' => Award::factory(),
            'application_number' => fake()->unique()->bothify('APP-####-????'),
            'application_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'application_status' => fake()->randomElement(['pending', 'accepted', 'rejected', 'under_review']),
            'jamb_score' => fake()->numberBetween(200, 400),
            'supporting_documents' => 'uploaded',
        ];
    }
}
