<?php

namespace Database\Factories;

use App\Models\Artista;
use App\Models\Personagem;
use App\Models\PersonagemCriador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonagemCriador>
 */
class PersonagemCriadorFactory extends Factory
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
            'artista_codigo' => Artista::factory(),
        ];
    }
}
