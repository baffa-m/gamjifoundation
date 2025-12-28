<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'content' => fake()->paragraphs(3, true),
            'description' => fake()->paragraph(), // Was excerpt
            'category' => fake()->randomElement(['award_announcement', 'winner_announcement', 'general', 'event']),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'author_id' => User::factory(), // Assuming User is author, or could be hardcoded to admin
            'featured_image' => null,
            'is_published' => fake()->boolean(80),
        ];
    }
}
