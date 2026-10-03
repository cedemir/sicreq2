<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('command creates a user', function () {
    $this->artisan('app:create-user', ['--name' => 'Maria Souza', '--email' => 'maria@example.com'])
        ->expectsQuestion('Senha', 'senha-segura')
        ->expectsQuestion('Confirme a senha', 'senha-segura')
        ->assertSuccessful();

    $user = User::where('email', 'maria@example.com')->sole();

    expect($user->name)->toBe('Maria Souza')
        ->and(Hash::check('senha-segura', $user->password))->toBeTrue();
});

test('command refuses invalid data', function () {
    User::factory()->create(['email' => 'maria@example.com']);

    $this->artisan('app:create-user', ['--name' => 'Maria Souza', '--email' => 'maria@example.com'])
        ->expectsQuestion('Senha', 'curta')
        ->expectsQuestion('Confirme a senha', 'outra')
        ->assertFailed();

    expect(User::count())->toBe(1);
});
