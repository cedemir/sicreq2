<?php

namespace Database\Factories;

use App\Models\Tamanho;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tamanho>
 */
class TamanhoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tamanho_descricao' => fake()->unique()->words(2, true),
            'tamanho_linha' => fake()->randomFloat(1, 10, 40),
            'tamanho_coluna' => fake()->randomFloat(1, 10, 40),
        ];
    }
}
