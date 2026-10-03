<?php

namespace App\Http\Controllers;

use App\Http\Requests\TamanhoRequest;
use App\Models\Tamanho;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TamanhoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tamanhos = Tamanho::query()
            ->search($request->string('busca')->toString())
            ->orderBy('tamanho_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('tamanhos.index', ['tamanhos' => $tamanhos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tamanho = new Tamanho;

        return view('tamanhos.create', ['tamanho' => $tamanho]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TamanhoRequest $request): RedirectResponse
    {
        Tamanho::create($request->validated());

        return to_route('tamanhos.index')->with('success', 'Tamanho cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tamanho $tamanho): View
    {
        return view('tamanhos.edit', ['tamanho' => $tamanho]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TamanhoRequest $request, Tamanho $tamanho): RedirectResponse
    {
        $tamanho->update($request->validated());

        return to_route('tamanhos.index')->with('success', 'Tamanho atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tamanho $tamanho): RedirectResponse
    {
        try {
            $tamanho->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o tamanho: existem registros vinculados.');
        }

        return to_route('tamanhos.index')->with('success', 'Tamanho excluído com sucesso.');
    }
}
