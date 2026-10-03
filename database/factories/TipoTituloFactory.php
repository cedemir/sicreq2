<?php

namespace Database\Factories;

use App\Models\TipoTitulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TipoTitulo>
 */
class TipoTituloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipotitulo_descricao' => fake()->unique()->words(2, true),
        ];
    }
}
