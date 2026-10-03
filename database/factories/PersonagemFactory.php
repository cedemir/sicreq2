<?php

namespace Database\Factories;

use App\Models\Personagem;
use App\Models\Universo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personagem>
 */
class PersonagemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'personagem_descricao' => fake()->unique()->firstName(),
            'universo_codigo' => Universo::factory(),
            'personagem_origem' => fake()->text(200),
        ];
    }
}
