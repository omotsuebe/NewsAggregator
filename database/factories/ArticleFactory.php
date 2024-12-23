<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->word,
            'category' => $this->faker->word,
            'source' => $this->faker->word,
            'published_at' => $this->faker->dateTime,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
