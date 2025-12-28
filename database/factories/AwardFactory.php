<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sponsor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sponsor_id' => Sponsor::factory(),
            'title' => fake()->sentence(4) . ' Scholarship',
            'description' => fake()->paragraph(),
            'deadline' => fake()->dateTimeBetween('now', '+2 months'),
            'required_documents' => 'JAMB Result, WAEC Result, Birth Certificate',
            'amount' => fake()->numberBetween(50000, 500000),
            'number_of_awards' => fake()->numberBetween(1, 10),
            'category' => fake()->randomElement(['jamb', 'waec', 'general']), // Matches migration enum
            'eligibility_criteria' => [fake()->sentence(), fake()->sentence()],
            'application_start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'application_end_date' => fake()->dateTimeBetween('now', '+2 months'),
            'announcement_date' => fake()->dateTimeBetween('+2 months', '+3 months'),
            'status' => fake()->randomElement(['active', 'draft', 'suspended']),
            'award_image' => null,
        ];
    }
}
