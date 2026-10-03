<?php

use App\Http\Controllers\ArmazenagemController;
use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\ArtistaHistoriaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditoraController;
use App\Http\Controllers\ExemplarController;
use App\Http\Controllers\ExemplarLoteController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\HistoriaPersonagemController;
use App\Http\Controllers\ImagemPersonagemController;
use App\Http\Controllers\PersonagemController;
use App\Http\Controllers\PersonagemCriadorController;
use App\Http\Controllers\RelatorioExemplarController;
use App\Http\Controllers\SituacaoTituloController;
use App\Http\Controllers\TamanhoController;
use App\Http\Controllers\TipoTituloController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\UniversoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('armazenagens', ArmazenagemController::class)->except('show')->parameters(['armazenagens' => 'armazenagem']);
    Route::resource('artista-historias', ArtistaHistoriaController::class)->except('show')->parameters(['artista-historias' => 'artista_historia']);
    Route::resource('artistas', ArtistaController::class)->except('show')->parameters(['artistas' => 'artista']);
    Route::resource('editoras', EditoraController::class)->except('show')->parameters(['editoras' => 'editora']);
    Route::get('exemplares/lote', [ExemplarLoteController::class, 'create'])->name('exemplares.lote.create');
    Route::post('exemplares/lote', [ExemplarLoteController::class, 'store'])->name('exemplares.lote.store');
    Route::get('exemplares/relatorio', RelatorioExemplarController::class)->name('exemplares.relatorio');
    Route::resource('exemplares', ExemplarController::class)->except('show')->parameters(['exemplares' => 'exemplar']);
    Route::resource('historia-personagens', HistoriaPersonagemController::class)->except('show')->parameters(['historia-personagens' => 'historia_personagem']);
    Route::resource('historias', HistoriaController::class)->except('show')->parameters(['historias' => 'historia']);
    Route::resource('imagens-personagem', ImagemPersonagemController::class)->except('show')->parameters(['imagens-personagem' => 'imagem_personagem']);
    Route::resource('personagem-criadores', PersonagemCriadorController::class)->except('show')->parameters(['personagem-criadores' => 'personagem_criador']);
    Route::resource('personagens', PersonagemController::class)->except('show')->parameters(['personagens' => 'personagem']);
    Route::resource('situacoes-titulo', SituacaoTituloController::class)->except('show')->parameters(['situacoes-titulo' => 'situacao_titulo']);
    Route::resource('tamanhos', TamanhoController::class)->except('show')->parameters(['tamanhos' => 'tamanho']);
    Route::resource('tipos-titulo', TipoTituloController::class)->except('show')->parameters(['tipos-titulo' => 'tipo_titulo']);
    Route::resource('titulos', TituloController::class)->except('show')->parameters(['titulos' => 'titulo']);
    Route::resource('universos', UniversoController::class)->except('show')->parameters(['universos' => 'universo']);
    Route::resource('usuarios', UserController::class)->except('show')->parameters(['usuarios' => 'user']);
});
