<?php

namespace App\Http\Controllers;

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
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the record count of every table.
     */
    public function __invoke(): View
    {
        $counts = [
            'titulos' => Titulo::count(),
            'exemplares' => Exemplar::count(),
            'historias' => Historia::count(),
            'personagens' => Personagem::count(),
            'artistas' => Artista::count(),
            'editoras' => Editora::count(),
            'universos' => Universo::count(),
            'tipos-titulo' => TipoTitulo::count(),
            'situacoes-titulo' => SituacaoTitulo::count(),
            'tamanhos' => Tamanho::count(),
            'armazenagens' => Armazenagem::count(),
            'imagens-personagem' => ImagemPersonagem::count(),
            'personagem-criadores' => PersonagemCriador::count(),
            'artista-historias' => ArtistaHistoria::count(),
            'historia-personagens' => HistoriaPersonagem::count(),
            'usuarios' => User::count(),
        ];

        return view('dashboard', ['counts' => $counts]);
    }
}
