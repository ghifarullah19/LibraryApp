<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4), // Judul dengan 4 kata
            'description' => fake()->paragraph(),
            'author_id' => Author::inRandomOrder()->first()->id ?? Author::factory(),
            'publisher_id' => Publisher::inRandomOrder()->first()->id ?? Publisher::factory(),
            'publish_year' => fake()->year(),
        ];
    }
}
