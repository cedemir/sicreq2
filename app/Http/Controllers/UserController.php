<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $users = User::query()
            ->search($request->string('busca')->toString())
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('usuarios.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('usuarios.create', ['user' => new User]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return to_route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('usuarios.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update(array_filter(
            $request->validated(),
            fn (mixed $value, string $key): bool => $key !== 'password' || filled($value),
            ARRAY_FILTER_USE_BOTH,
        ));

        return to_route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Você não pode excluir o próprio usuário.');
        }

        $user->delete();

        return to_route('usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
