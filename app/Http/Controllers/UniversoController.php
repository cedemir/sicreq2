<?php

namespace App\Http\Controllers;

use App\Http\Requests\UniversoRequest;
use App\Models\Universo;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UniversoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $universos = Universo::query()
            ->search($request->string('busca')->toString())
            ->orderBy('universo_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('universos.index', ['universos' => $universos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $universo = new Universo;

        return view('universos.create', ['universo' => $universo]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UniversoRequest $request): RedirectResponse
    {
        Universo::create($request->validated());

        return to_route('universos.index')->with('success', 'Universo cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Universo $universo): View
    {
        return view('universos.edit', ['universo' => $universo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UniversoRequest $request, Universo $universo): RedirectResponse
    {
        $universo->update($request->validated());

        return to_route('universos.index')->with('success', 'Universo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Universo $universo): RedirectResponse
    {
        try {
            $universo->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o universo: existem registros vinculados.');
        }

        return to_route('universos.index')->with('success', 'Universo excluído com sucesso.');
    }
}
