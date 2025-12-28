<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applicant>
 */
class ApplicantFactory extends Factory
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
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female']),
            'state_of_origin' => fake()->state(),
            'lga' => fake()->city(),
            'school_name' => fake()->company() . ' School',
            'jamb_reg_number' => fake()->bothify('??########'),
            'waec_reg_number' => fake()->bothify('??########'),
            'profile_picture' => null,
            'waec_results' => [], // Can elaborate if needed
        ];
    }
}
