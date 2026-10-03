<?php

namespace App\Http\Controllers;

use App\Http\Requests\TituloRequest;
use App\Models\Editora;
use App\Models\SituacaoTitulo;
use App\Models\TipoTitulo;
use App\Models\Titulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TituloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $titulos = Titulo::query()
            ->with(['editora', 'tipoTitulo', 'situacao'])
            ->search($request->string('busca')->toString())
            ->orderBy('titulo_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('titulos.index', ['titulos' => $titulos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $titulo = new Titulo;

        return view('titulos.create', ['titulo' => $titulo, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TituloRequest $request): RedirectResponse
    {
        Titulo::create($request->validated());

        return to_route('titulos.index')->with('success', 'Título cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Titulo $titulo): View
    {
        return view('titulos.edit', ['titulo' => $titulo, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TituloRequest $request, Titulo $titulo): RedirectResponse
    {
        $titulo->update($request->validated());

        return to_route('titulos.index')->with('success', 'Título atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Titulo $titulo): RedirectResponse
    {
        try {
            $titulo->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o título: existem registros vinculados.');
        }

        return to_route('titulos.index')->with('success', 'Título excluído com sucesso.');
    }

    /**
     * Get the select options used by the create and edit forms.
     *
     * @return array<string, Collection<int, string>>
     */
    private function formOptions(): array
    {
        return [
            'editoras' => Editora::query()->orderBy('editora_descricao')->pluck('editora_descricao', 'editora_codigo'),
            'tiposTitulo' => TipoTitulo::query()->orderBy('tipotitulo_descricao')->pluck('tipotitulo_descricao', 'tipotitulo_codigo'),
            'situacoesTitulo' => SituacaoTitulo::query()->orderBy('situacao_descricao')->pluck('situacao_descricao', 'situacao_codigo'),
        ];
    }
}
