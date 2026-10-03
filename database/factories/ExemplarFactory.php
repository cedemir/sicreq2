<?php

namespace Database\Factories;

use App\Models\Exemplar;
use App\Models\Titulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exemplar>
 */
class ExemplarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo_codigo' => Titulo::factory(),
            'exemplar_codigo' => fake()->unique()->numberBetween(1, 1000000),
            'exemplar_subtitulo' => fake()->unique()->sentence(3),
            'exemplar_capa' => null,
            'exemplar_data' => fake()->dateTimeBetween('-50 years'),
            'armazenagem_codigo' => null,
            'tamanho_codigo' => null,
            'exemplar_situacao' => fake()->numberBetween(0, 3),
            'exemplar_numeropagina' => fake()->numberBetween(1, 200),
        ];
    }
}
