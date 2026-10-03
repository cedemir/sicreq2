<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTituloRequest;
use App\Models\TipoTitulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoTituloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tiposTitulo = TipoTitulo::query()
            ->search($request->string('busca')->toString())
            ->orderBy('tipotitulo_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('tipos-titulo.index', ['tiposTitulo' => $tiposTitulo]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoTitulo = new TipoTitulo;

        return view('tipos-titulo.create', ['tipoTitulo' => $tipoTitulo]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoTituloRequest $request): RedirectResponse
    {
        TipoTitulo::create($request->validated());

        return to_route('tipos-titulo.index')->with('success', 'Tipo de título cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoTitulo $tipoTitulo): View
    {
        return view('tipos-titulo.edit', ['tipoTitulo' => $tipoTitulo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoTituloRequest $request, TipoTitulo $tipoTitulo): RedirectResponse
    {
        $tipoTitulo->update($request->validated());

        return to_route('tipos-titulo.index')->with('success', 'Tipo de título atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoTitulo $tipoTitulo): RedirectResponse
    {
        try {
            $tipoTitulo->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o tipo de título: existem registros vinculados.');
        }

        return to_route('tipos-titulo.index')->with('success', 'Tipo de título excluído com sucesso.');
    }
}
