<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonagemRequest;
use App\Models\Personagem;
use App\Models\Universo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PersonagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $personagens = Personagem::query()
            ->with(['universo'])
            ->search($request->string('busca')->toString())
            ->orderBy('personagem_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('personagens.index', ['personagens' => $personagens]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $personagem = new Personagem;

        return view('personagens.create', ['personagem' => $personagem, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonagemRequest $request): RedirectResponse
    {
        Personagem::create($request->validated());

        return to_route('personagens.index')->with('success', 'Personagem cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personagem $personagem): View
    {
        return view('personagens.edit', ['personagem' => $personagem, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonagemRequest $request, Personagem $personagem): RedirectResponse
    {
        $personagem->update($request->validated());

        return to_route('personagens.index')->with('success', 'Personagem atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personagem $personagem): RedirectResponse
    {
        try {
            $personagem->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o personagem: existem registros vinculados.');
        }

        return to_route('personagens.index')->with('success', 'Personagem excluído com sucesso.');
    }

    /**
     * Get the select options used by the create and edit forms.
     *
     * @return array<string, Collection<int, string>>
     */
    private function formOptions(): array
    {
        return [
            'universos' => Universo::query()->orderBy('universo_descricao')->pluck('universo_descricao', 'universo_codigo'),
        ];
    }
}
