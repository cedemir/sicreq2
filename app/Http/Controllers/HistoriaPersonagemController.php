<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoriaPersonagemRequest;
use App\Models\HistoriaPersonagem;
use App\Models\Personagem;
use App\Models\Titulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HistoriaPersonagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $historiaPersonagens = HistoriaPersonagem::query()
            ->with(['titulo', 'personagem'])
            ->search($request->string('busca')->toString())
            ->orderBy('titulo_codigo')
            ->orderBy('exemplar_codigo')
            ->orderBy('historia_codigo')
            ->paginate(15)
            ->withQueryString();

        return view('historia-personagens.index', ['historiaPersonagens' => $historiaPersonagens]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $historiaPersonagem = new HistoriaPersonagem;

        return view('historia-personagens.create', ['historiaPersonagem' => $historiaPersonagem, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistoriaPersonagemRequest $request): RedirectResponse
    {
        HistoriaPersonagem::create($request->validated());

        return to_route('historia-personagens.index')->with('success', 'Personagem em história cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriaPersonagem $historiaPersonagem): View
    {
        return view('historia-personagens.edit', ['historiaPersonagem' => $historiaPersonagem, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HistoriaPersonagemRequest $request, HistoriaPersonagem $historiaPersonagem): RedirectResponse
    {
        $historiaPersonagem->update($request->validated());

        return to_route('historia-personagens.index')->with('success', 'Personagem em história atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriaPersonagem $historiaPersonagem): RedirectResponse
    {
        try {
            $historiaPersonagem->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o personagem em história: existem registros vinculados.');
        }

        return to_route('historia-personagens.index')->with('success', 'Personagem em história excluído com sucesso.');
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
            'personagens' => Personagem::query()->orderBy('personagem_descricao')->pluck('personagem_descricao', 'personagem_codigo'),
        ];
    }
}
