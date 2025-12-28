<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->catchPhrase(),
            'subtitle' => fake()->sentence(),
            'image' => 'hero-slides/default.jpg', // Placeholder
            'cta_text' => fake()->randomElement(['Apply Now', 'Learn More', 'Contact Us']),
            'cta_link' => fake()->url(),
            'order' => fake()->numberBetween(1, 10),
            'is_active' => fake()->boolean(90),
        ];
    }
}
