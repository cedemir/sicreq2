<?php

namespace Database\Factories;

use App\Models\Exemplar;
use App\Models\Historia;
use App\Models\Titulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Historia>
 */
class HistoriaFactory extends Factory
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
            'exemplar_codigo' => fn (array $attributes): int => Exemplar::factory()->create(['titulo_codigo' => $attributes['titulo_codigo']])->exemplar_codigo,
            'historia_numero' => fake()->unique()->numberBetween(1, 1000000),
            'historia_titulo' => fake()->unique()->words(3, true),
            'historia_resumo' => fake()->text(200),
            'historia_cor' => fake()->boolean(),
            'historia_original' => fake()->words(3, true),
        ];
    }
}
