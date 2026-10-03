<?php

namespace Database\Factories;

use App\Models\Artista;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Artista>
 */
class ArtistaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artista_nome' => fake()->unique()->name(),
            'artista_historico' => fake()->text(200),
            'artista_foto' => null,
        ];
    }
}
