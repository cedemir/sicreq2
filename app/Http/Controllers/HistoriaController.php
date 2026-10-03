<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoriaRequest;
use App\Models\Historia;
use App\Models\Titulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HistoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $historias = Historia::query()
            ->with(['titulo'])
            ->search($request->string('busca')->toString())
            ->orderBy('titulo_codigo')
            ->orderBy('exemplar_codigo')
            ->orderBy('historia_numero')
            ->paginate(15)
            ->withQueryString();

        return view('historias.index', ['historias' => $historias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $historia = new Historia;

        return view('historias.create', ['historia' => $historia, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistoriaRequest $request): RedirectResponse
    {
        Historia::create($request->validated());

        return to_route('historias.index')->with('success', 'História cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Historia $historia): View
    {
        return view('historias.edit', ['historia' => $historia, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HistoriaRequest $request, Historia $historia): RedirectResponse
    {
        $historia->update($request->validated());

        return to_route('historias.index')->with('success', 'História atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Historia $historia): RedirectResponse
    {
        try {
            $historia->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir a história: existem registros vinculados.');
        }

        return to_route('historias.index')->with('success', 'História excluída com sucesso.');
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
        ];
    }
}
