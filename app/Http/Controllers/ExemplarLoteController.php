<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExemplarLoteRequest;
use App\Models\Armazenagem;
use App\Models\Exemplar;
use App\Models\Tamanho;
use App\Models\Titulo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExemplarLoteController extends Controller
{
    /**
     * Show the form for generating a range of copies.
     */
    public function create(): View
    {
        return view('exemplares.lote', [
            'maxExemplares' => ExemplarLoteRequest::MAX_EXEMPLARES,
            'titulos' => Titulo::query()->orderBy('titulo_descricao')->pluck('titulo_descricao', 'titulo_codigo'),
            'armazenagens' => Armazenagem::query()->orderBy('armazenagem_descricao')->pluck('armazenagem_descricao', 'armazenagem_codigo'),
            'tamanhos' => Tamanho::query()->orderBy('tamanho_descricao')->pluck('tamanho_descricao', 'tamanho_codigo'),
        ]);
    }

    /**
     * Generate every copy in the range that does not exist yet for the title.
     *
     * Copies that already exist are kept untouched and reported back.
     */
    public function store(ExemplarLoteRequest $request): RedirectResponse
    {
        $titulo = Titulo::findOrFail($request->integer('titulo_codigo'));
        $numeros = $request->numeros();

        $existentes = DB::transaction(function () use ($request, $titulo, $numeros): array {
            $existentes = Exemplar::query()
                ->whereBelongsTo($titulo)
                ->whereIn('exemplar_codigo', $numeros)
                ->orderBy('exemplar_codigo')
                ->pluck('exemplar_codigo')
                ->all();

            $linhas = collect($numeros)
                ->diff($existentes)
                ->map(fn (int $numero): array => [
                    'titulo_codigo' => $titulo->titulo_codigo,
                    'exemplar_codigo' => $numero,
                    'armazenagem_codigo' => $request->validated('armazenagem_codigo'),
                    'tamanho_codigo' => $request->validated('tamanho_codigo'),
                    'exemplar_numeropagina' => $request->validated('exemplar_numeropagina'),
                ]);

            $linhas->chunk(200)->each(fn ($lote) => Exemplar::query()->insert($lote->values()->all()));

            return $existentes;
        });

        $gerados = count($numeros) - count($existentes);
        $mensagem = $gerados === 1 ? '1 exemplar gerado' : "{$gerados} exemplares gerados";
        $mensagem .= " para {$titulo->titulo_descricao}.";

        if ($existentes !== []) {
            $listados = implode(', ', array_slice($existentes, 0, 20));
            $restantes = count($existentes) - 20;
            $mensagem .= " Já existiam e foram mantidos: nº {$listados}".($restantes > 0 ? " e mais {$restantes}" : '').'.';
        }

        return to_route('exemplares.index', ['busca' => $titulo->titulo_descricao])
            ->with('success', $mensagem);
    }
}
