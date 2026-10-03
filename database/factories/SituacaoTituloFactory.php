<?php

namespace Database\Factories;

use App\Models\SituacaoTitulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SituacaoTitulo>
 */
class SituacaoTituloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'situacao_descricao' => fake()->unique()->words(2, true),
        ];
    }
}
