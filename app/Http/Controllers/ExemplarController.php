<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExemplarRequest;
use App\Models\Armazenagem;
use App\Models\Exemplar;
use App\Models\Tamanho;
use App\Models\Titulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExemplarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $exemplares = Exemplar::query()
            ->with(['titulo', 'armazenagem', 'tamanho'])
            ->search($request->string('busca')->toString())
            ->orderBy('titulo_codigo')
            ->orderBy('exemplar_codigo')
            ->paginate(15)
            ->withQueryString();

        return view('exemplares.index', ['exemplares' => $exemplares]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $exemplar = new Exemplar;

        return view('exemplares.create', ['exemplar' => $exemplar, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExemplarRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('exemplar_capa');

        if ($request->hasFile('exemplar_capa')) {
            $data['exemplar_capa'] = $request->file('exemplar_capa')->store('capas', 'public');
        }

        Exemplar::create($data);

        return to_route('exemplares.index')->with('success', 'Exemplar cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exemplar $exemplar): View
    {
        return view('exemplares.edit', ['exemplar' => $exemplar, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExemplarRequest $request, Exemplar $exemplar): RedirectResponse
    {
        $data = $request->safe()->except('exemplar_capa');

        if ($request->hasFile('exemplar_capa')) {
            if ($exemplar->exemplar_capa) {
                Storage::disk('public')->delete($exemplar->exemplar_capa);
            }

            $data['exemplar_capa'] = $request->file('exemplar_capa')->store('capas', 'public');
        }

        $exemplar->update($data);

        return to_route('exemplares.index')->with('success', 'Exemplar atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exemplar $exemplar): RedirectResponse
    {
        try {
            $exemplar->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o exemplar: existem registros vinculados.');
        }

        if ($exemplar->exemplar_capa) {
            Storage::disk('public')->delete($exemplar->exemplar_capa);
        }

        return to_route('exemplares.index')->with('success', 'Exemplar excluído com sucesso.');
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
            'armazenagens' => Armazenagem::query()->orderBy('armazenagem_descricao')->pluck('armazenagem_descricao', 'armazenagem_codigo'),
            'tamanhos' => Tamanho::query()->orderBy('tamanho_descricao')->pluck('tamanho_descricao', 'tamanho_codigo'),
        ];
    }
}
