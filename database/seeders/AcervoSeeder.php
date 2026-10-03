<?php

namespace Database\Seeders;

use App\Models\Armazenagem;
use App\Models\Artista;
use App\Models\ArtistaHistoria;
use App\Models\Editora;
use App\Models\Exemplar;
use App\Models\Historia;
use App\Models\HistoriaPersonagem;
use App\Models\ImagemPersonagem;
use App\Models\Personagem;
use App\Models\PersonagemCriador;
use App\Models\SituacaoTitulo;
use App\Models\Tamanho;
use App\Models\TipoTitulo;
use App\Models\Titulo;
use App\Models\Universo;
use Illuminate\Database\Seeder;

class AcervoSeeder extends Seeder
{
    /**
     * Seed the collection with interconnected sample data.
     */
    public function run(): void
    {
        $armazenagens = Armazenagem::factory(3)->create();
        $tamanhos = Tamanho::factory(3)->create();
        $artistas = Artista::factory(10)->create();
        $personagens = Personagem::factory(15)
            ->recycle(Universo::factory(3)->create())
            ->create();

        $personagens->each(function (Personagem $personagem) use ($artistas): void {
            PersonagemCriador::factory()->create([
                'personagem_codigo' => $personagem->personagem_codigo,
                'artista_codigo' => $artistas->random()->artista_codigo,
            ]);
            ImagemPersonagem::factory()->create(['personagem_codigo' => $personagem->personagem_codigo]);
        });

        $titulos = Titulo::factory(8)
            ->recycle(Editora::factory(4)->create())
            ->recycle(TipoTitulo::factory(3)->create())
            ->recycle(SituacaoTitulo::factory(3)->create())
            ->create();

        foreach ($titulos as $titulo) {
            foreach (range(1, 3) as $numero) {
                $exemplar = Exemplar::factory()->create([
                    'titulo_codigo' => $titulo->titulo_codigo,
                    'exemplar_codigo' => $numero,
                    'armazenagem_codigo' => $armazenagens->random()->armazenagem_codigo,
                    'tamanho_codigo' => $tamanhos->random()->tamanho_codigo,
                ]);

                foreach (range(1, 2) as $numeroHistoria) {
                    $historia = Historia::factory()->create([
                        'titulo_codigo' => $exemplar->titulo_codigo,
                        'exemplar_codigo' => $exemplar->exemplar_codigo,
                        'historia_numero' => $numeroHistoria,
                    ]);

                    $chaveHistoria = [
                        'titulo_codigo' => $historia->titulo_codigo,
                        'exemplar_codigo' => $historia->exemplar_codigo,
                        'historia_codigo' => $historia->historia_numero,
                    ];

                    ArtistaHistoria::factory()->create([
                        ...$chaveHistoria,
                        'artista_codigo' => $artistas->random()->artista_codigo,
                    ]);
                    HistoriaPersonagem::factory()->create([
                        ...$chaveHistoria,
                        'personagem_codigo' => $personagens->random()->personagem_codigo,
                    ]);
                }
            }
        }
    }
}
