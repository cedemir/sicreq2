<?php

namespace App\Http\Controllers;

use App\Http\Requests\SituacaoTituloRequest;
use App\Models\SituacaoTitulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SituacaoTituloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $situacoesTitulo = SituacaoTitulo::query()
            ->search($request->string('busca')->toString())
            ->orderBy('situacao_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('situacoes-titulo.index', ['situacoesTitulo' => $situacoesTitulo]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $situacaoTitulo = new SituacaoTitulo;

        return view('situacoes-titulo.create', ['situacaoTitulo' => $situacaoTitulo]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SituacaoTituloRequest $request): RedirectResponse
    {
        SituacaoTitulo::create($request->validated());

        return to_route('situacoes-titulo.index')->with('success', 'Situação de título cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SituacaoTitulo $situacaoTitulo): View
    {
        return view('situacoes-titulo.edit', ['situacaoTitulo' => $situacaoTitulo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SituacaoTituloRequest $request, SituacaoTitulo $situacaoTitulo): RedirectResponse
    {
        $situacaoTitulo->update($request->validated());

        return to_route('situacoes-titulo.index')->with('success', 'Situação de título atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SituacaoTitulo $situacaoTitulo): RedirectResponse
    {
        try {
            $situacaoTitulo->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir a situação de título: existem registros vinculados.');
        }

        return to_route('situacoes-titulo.index')->with('success', 'Situação de título excluída com sucesso.');
    }
}
