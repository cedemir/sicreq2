<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonagemCriadorRequest;
use App\Models\Artista;
use App\Models\Personagem;
use App\Models\PersonagemCriador;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PersonagemCriadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $personagemCriadores = PersonagemCriador::query()
            ->with(['personagem', 'artista'])
            ->search($request->string('busca')->toString())
            ->orderBy('personagem_codigo')
            ->orderBy('artista_codigo')
            ->paginate(15)
            ->withQueryString();

        return view('personagem-criadores.index', ['personagemCriadores' => $personagemCriadores]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $personagemCriador = new PersonagemCriador;

        return view('personagem-criadores.create', ['personagemCriador' => $personagemCriador, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonagemCriadorRequest $request): RedirectResponse
    {
        PersonagemCriador::create($request->validated());

        return to_route('personagem-criadores.index')->with('success', 'Criador de personagem cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonagemCriador $personagemCriador): View
    {
        return view('personagem-criadores.edit', ['personagemCriador' => $personagemCriador, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonagemCriadorRequest $request, PersonagemCriador $personagemCriador): RedirectResponse
    {
        $personagemCriador->update($request->validated());

        return to_route('personagem-criadores.index')->with('success', 'Criador de personagem atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonagemCriador $personagemCriador): RedirectResponse
    {
        try {
            $personagemCriador->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o criador de personagem: existem registros vinculados.');
        }

        return to_route('personagem-criadores.index')->with('success', 'Criador de personagem excluído com sucesso.');
    }

    /**
     * Get the select options used by the create and edit forms.
     *
     * @return array<string, Collection<int, string>>
     */
    private function formOptions(): array
    {
        return [
            'personagens' => Personagem::query()->orderBy('personagem_descricao')->pluck('personagem_descricao', 'personagem_codigo'),
            'artistas' => Artista::query()->orderBy('artista_nome')->pluck('artista_nome', 'artista_codigo'),
        ];
    }
}
