<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Publisher>
 */
class PublisherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(), // Menghasilkan nama perusahaan acak
            'address' => fake()->address(), // Menghasilkan alamat acak
        ];
    }
}
