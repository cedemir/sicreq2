<?php

namespace Database\Factories;

use App\Models\Exemplar;
use App\Models\Historia;
use App\Models\HistoriaPersonagem;
use App\Models\Personagem;
use App\Models\Titulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HistoriaPersonagem>
 */
class HistoriaPersonagemFactory extends Factory
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
            'historia_codigo' => fn (array $attributes): int => Historia::factory()->create(['titulo_codigo' => $attributes['titulo_codigo'], 'exemplar_codigo' => $attributes['exemplar_codigo']])->historia_numero,
            'personagem_codigo' => Personagem::factory(),
        ];
    }
}
