<?php

namespace Database\Factories;

use App\Models\Editora;
use App\Models\SituacaoTitulo;
use App\Models\TipoTitulo;
use App\Models\Titulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Titulo>
 */
class TituloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo_descricao' => fake()->unique()->words(3, true),
            'editora_codigo' => Editora::factory(),
            'tipotitulo_codigo' => TipoTitulo::factory(),
            'situacao_codigo' => SituacaoTitulo::factory(),
            'titulo_periodicidade' => fake()->numberBetween(1, 200),
            'titulo_origem' => fake()->randomElement(['Brasil', 'EUA', 'Japão', 'Itália', 'França']),
        ];
    }
}
