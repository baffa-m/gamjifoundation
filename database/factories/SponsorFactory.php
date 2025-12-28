<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsor>
 */
class SponsorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'organization_name' => fake()->company(),
            'sponsor_type' => fake()->randomElement(['individual', 'corporate', 'government', 'ngo']),
            'website' => fake()->url(),
            'description' => fake()->paragraph(),
            'address' => fake()->address(),
            'registration_number' => fake()->bothify('REG-####-####'),
            'verification_status' => fake()->randomElement(['pending', 'verified', 'rejected']),
            'verified_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
