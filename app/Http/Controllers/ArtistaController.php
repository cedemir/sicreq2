<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistaRequest;
use App\Models\Artista;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArtistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $artistas = Artista::query()
            ->search($request->string('busca')->toString())
            ->orderBy('artista_nome')
            ->paginate(15)
            ->withQueryString();

        return view('artistas.index', ['artistas' => $artistas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $artista = new Artista;

        return view('artistas.create', ['artista' => $artista]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArtistaRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('artista_foto');

        if ($request->hasFile('artista_foto')) {
            $data['artista_foto'] = $request->file('artista_foto')->store('artistas', 'public');
        }

        Artista::create($data);

        return to_route('artistas.index')->with('success', 'Artista cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artista $artista): View
    {
        return view('artistas.edit', ['artista' => $artista]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArtistaRequest $request, Artista $artista): RedirectResponse
    {
        $data = $request->safe()->except('artista_foto');

        if ($request->hasFile('artista_foto')) {
            if ($artista->artista_foto) {
                Storage::disk('public')->delete($artista->artista_foto);
            }

            $data['artista_foto'] = $request->file('artista_foto')->store('artistas', 'public');
        }

        $artista->update($data);

        return to_route('artistas.index')->with('success', 'Artista atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artista $artista): RedirectResponse
    {
        try {
            $artista->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir o artista: existem registros vinculados.');
        }

        if ($artista->artista_foto) {
            Storage::disk('public')->delete($artista->artista_foto);
        }

        return to_route('artistas.index')->with('success', 'Artista excluído com sucesso.');
    }
}
