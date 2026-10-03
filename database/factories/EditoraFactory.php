<?php

namespace Database\Factories;

use App\Models\Editora;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Editora>
 */
class EditoraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'editora_descricao' => fake()->unique()->company(),
        ];
    }
}
