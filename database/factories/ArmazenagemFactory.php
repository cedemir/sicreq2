<?php

namespace Database\Factories;

use App\Models\Armazenagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Armazenagem>
 */
class ArmazenagemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'armazenagem_descricao' => fake()->unique()->words(2, true),
        ];
    }
}
