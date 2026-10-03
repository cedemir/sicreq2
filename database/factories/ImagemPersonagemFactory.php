<?php

namespace Database\Factories;

use App\Models\ImagemPersonagem;
use App\Models\Personagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ImagemPersonagem>
 */
class ImagemPersonagemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'personagem_codigo' => Personagem::factory(),
            'imagem_codigo' => fake()->unique()->numberBetween(1, 1000000),
            'imagem_descricao' => fake()->unique()->sentence(4),
        ];
    }
}
