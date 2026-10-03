<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditoraRequest;
use App\Models\Editora;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EditoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $editoras = Editora::query()
            ->search($request->string('busca')->toString())
            ->orderBy('editora_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('editoras.index', ['editoras' => $editoras]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $editora = new Editora;

        return view('editoras.create', ['editora' => $editora]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EditoraRequest $request): RedirectResponse
    {
        Editora::create($request->validated());

        return to_route('editoras.index')->with('success', 'Editora cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Editora $editora): View
    {
        return view('editoras.edit', ['editora' => $editora]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditoraRequest $request, Editora $editora): RedirectResponse
    {
        $editora->update($request->validated());

        return to_route('editoras.index')->with('success', 'Editora atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Editora $editora): RedirectResponse
    {
        try {
            $editora->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir a editora: existem registros vinculados.');
        }

        return to_route('editoras.index')->with('success', 'Editora excluída com sucesso.');
    }
}
