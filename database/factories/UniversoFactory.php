<?php

namespace Database\Factories;

use App\Models\Universo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Universo>
 */
class UniversoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'universo_descricao' => fake()->unique()->words(2, true),
        ];
    }
}
