<?php

namespace Database\Factories;

use App\Enums\GameStatus;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(rand(1, 3), true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'synopsis' => fake()->paragraph(),
            'release_year' => fake()->numberBetween(2000, 2026),
            'rating' => fake()->randomFloat(1, 2, 5),
            'playtime_hours' => fake()->numberBetween(5, 200),
            'cover_url' => 'https://via.assets.so/game.png?id='.fake()->numberBetween(7, 99).'&q=95&w=300&h=450&fit=cover',
            'featured' => false,
            'status' => fake()->randomElement(GameStatus::cases()),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Un jeu « à la une ».
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }
}
