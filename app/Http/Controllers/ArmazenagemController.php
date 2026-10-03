<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArmazenagemRequest;
use App\Models\Armazenagem;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArmazenagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $armazenagens = Armazenagem::query()
            ->search($request->string('busca')->toString())
            ->orderBy('armazenagem_descricao')
            ->paginate(15)
            ->withQueryString();

        return view('armazenagens.index', ['armazenagens' => $armazenagens]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $armazenagem = new Armazenagem;

        return view('armazenagens.create', ['armazenagem' => $armazenagem]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArmazenagemRequest $request): RedirectResponse
    {
        Armazenagem::create($request->validated());

        return to_route('armazenagens.index')->with('success', 'Armazenagem cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Armazenagem $armazenagem): View
    {
        return view('armazenagens.edit', ['armazenagem' => $armazenagem]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArmazenagemRequest $request, Armazenagem $armazenagem): RedirectResponse
    {
        $armazenagem->update($request->validated());

        return to_route('armazenagens.index')->with('success', 'Armazenagem atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Armazenagem $armazenagem): RedirectResponse
    {
        try {
            $armazenagem->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir a armazenagem: existem registros vinculados.');
        }

        return to_route('armazenagens.index')->with('success', 'Armazenagem excluída com sucesso.');
    }
}
