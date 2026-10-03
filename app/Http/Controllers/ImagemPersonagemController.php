<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagemPersonagemRequest;
use App\Models\ImagemPersonagem;
use App\Models\Personagem;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ImagemPersonagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $imagensPersonagem = ImagemPersonagem::query()
            ->with(['personagem'])
            ->search($request->string('busca')->toString())
            ->orderBy('personagem_codigo')
            ->orderBy('imagem_codigo')
            ->paginate(15)
            ->withQueryString();

        return view('imagens-personagem.index', ['imagensPersonagem' => $imagensPersonagem]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $imagemPersonagem = new ImagemPersonagem;

        return view('imagens-personagem.create', ['imagemPersonagem' => $imagemPersonagem, ...$this->formOptions()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImagemPersonagemRequest $request): RedirectResponse
    {
        ImagemPersonagem::create($request->validated());

        return to_route('imagens-personagem.index')->with('success', 'Imagem de personagem cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImagemPersonagem $imagemPersonagem): View
    {
        return view('imagens-personagem.edit', ['imagemPersonagem' => $imagemPersonagem, ...$this->formOptions()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ImagemPersonagemRequest $request, ImagemPersonagem $imagemPersonagem): RedirectResponse
    {
        $imagemPersonagem->update($request->validated());

        return to_route('imagens-personagem.index')->with('success', 'Imagem de personagem atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImagemPersonagem $imagemPersonagem): RedirectResponse
    {
        try {
            $imagemPersonagem->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyViolation($exception)) {
                throw $exception;
            }

            return back()->with('error', 'Não é possível excluir a imagem de personagem: existem registros vinculados.');
        }

        return to_route('imagens-personagem.index')->with('success', 'Imagem de personagem excluída com sucesso.');
    }

    /**
     * Get the select options used by the create and edit forms.
     *
     * @return array<string, Collection<int, string>>
     */
    private function formOptions(): array
    {
        return [
            'personagens' => Personagem::query()->orderBy('personagem_descricao')->pluck('personagem_descricao', 'personagem_codigo'),
        ];
    }
}
