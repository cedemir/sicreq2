<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistaHistoriaRequest;
use App\Models\Artista;
use App\Models\ArtistaHistoria;
use App\Models\Titulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ArtistaHistoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $artistaHistorias = ArtistaHistoria::query()
            ->with(['titulo', 'artista'])
            ->search($request->string('busca')->toString())
            ->orderBy('titulo_codigo')
            ->orderBy('exemplar_codigo')
            ->orderBy('historia_codigo')
            ->paginate(15)
            ->withQueryString();

        return view('artista-historias.index', ['artistaHistorias' => $artistaHistorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $artistaHistoria = new ArtistaHistoria;

        return view('artista-historias.create', ['artistaHistoria' => $artistaHistoria, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArtistaHistoriaRequest $request): RedirectResponse
    {
        ArtistaHistoria::create($request->validated());

        return to_route('artista-historias.index')->with('success', 'Participação de artista cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArtistaHistoria $artistaHistoria): View
    {
        return view('artista-historias.edit', ['artistaHistoria' => $artistaHistoria, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArtistaHistoriaRequest $request, ArtistaHistoria $artistaHistoria): RedirectResponse
    {
        $artistaHistoria->update($request->validated());

        return to_route('artista-historias.index')->with('success', 'Participação de artista atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArtistaHistoria $artistaHistoria): RedirectResponse
    {
        try {
            $artistaHistoria->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir a participação de artista: existem registros vinculados.');
        }

        return to_route('artista-historias.index')->with('success', 'Participação de artista excluída com sucesso.');
    }

    /**
     * Get the select options used by the create and edit forms.
     *
     * @return array<string, Collection<int, string>>
     */
    private function formOptions(): array
    {
        return [
            'titulos' => Titulo::query()->orderBy('titulo_descricao')->pluck('titulo_descricao', 'titulo_codigo'),
            'artistas' => Artista::query()->orderBy('artista_nome')->pluck('artista_nome', 'artista_codigo'),
        ];
    }
}
