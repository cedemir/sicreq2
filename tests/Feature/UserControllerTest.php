<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = User::factory()->create(['name' => 'Administrador']);
    $this->actingAs($this->admin);
});

test('index lists the users', function () {
    $user = User::factory()->create();

    $this->get(route('usuarios.index'))
        ->assertOk()
        ->assertSee($user->name)
        ->assertSee($user->email);
});

test('index filters the users by search term', function () {
    User::factory()->create(['name' => 'Termo Procurado']);
    User::factory()->create(['name' => 'Outro Registro']);

    $this->get(route('usuarios.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('usuarios.create'))->assertOk();
});

test('store creates a user with a hashed password', function () {
    $this->post(route('usuarios.store'), [
        'name' => 'Maria Souza',
        'email' => 'maria@example.com',
        'password' => 'senha-segura',
        'password_confirmation' => 'senha-segura',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('usuarios.index'));

    $user = User::where('email', 'maria@example.com')->sole();

    expect($user->name)->toBe('Maria Souza')
        ->and(Hash::check('senha-segura', $user->password))->toBeTrue();
});

test('store validates the required fields', function () {
    $this->post(route('usuarios.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

test('store requires the password confirmation to match', function () {
    $this->post(route('usuarios.store'), [
        'name' => 'Maria Souza',
        'email' => 'maria@example.com',
        'password' => 'senha-segura',
        'password_confirmation' => 'outra-senha',
    ])->assertSessionHasErrors('password');

    $this->assertDatabaseMissing('users', ['email' => 'maria@example.com']);
});

test('store requires a password of at least 8 characters', function () {
    $this->post(route('usuarios.store'), [
        'name' => 'Maria Souza',
        'email' => 'maria@example.com',
        'password' => 'curta',
        'password_confirmation' => 'curta',
    ])->assertSessionHasErrors('password');
});

test('store rejects a duplicated e-mail', function () {
    $this->post(route('usuarios.store'), [
        'name' => 'Outra pessoa',
        'email' => $this->admin->email,
        'password' => 'senha-segura',
        'password_confirmation' => 'senha-segura',
    ])->assertSessionHasErrors('email');
});

test('edit form renders', function () {
    $user = User::factory()->create();

    $this->get(route('usuarios.edit', $user))->assertOk();
});

test('update keeps the current password when it is left blank', function () {
    $user = User::factory()->create();
    $originalHash = $user->password;

    $this->put(route('usuarios.update', $user), [
        'name' => 'Nome alterado',
        'email' => $user->email,
        'password' => '',
        'password_confirmation' => '',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('usuarios.index'));

    $user->refresh();

    expect($user->name)->toBe('Nome alterado')
        ->and($user->password)->toBe($originalHash);
});

test('update changes the password when a new one is given', function () {
    $user = User::factory()->create();

    $this->put(route('usuarios.update', $user), [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'nova-senha-123',
        'password_confirmation' => 'nova-senha-123',
    ])->assertSessionHasNoErrors();

    expect(Hash::check('nova-senha-123', $user->refresh()->password))->toBeTrue();
});

test('update allows keeping the same e-mail', function () {
    $this->put(route('usuarios.update', $this->admin), [
        'name' => 'Administrador',
        'email' => $this->admin->email,
    ])->assertSessionHasNoErrors();
});

test('destroy deletes another user', function () {
    $user = User::factory()->create();

    $this->delete(route('usuarios.destroy', $user))
        ->assertRedirect(route('usuarios.index'));

    $this->assertModelMissing($user);
});

test('destroy refuses to delete the logged in user', function () {
    $this->from(route('usuarios.index'))
        ->delete(route('usuarios.destroy', $this->admin))
        ->assertRedirect(route('usuarios.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($this->admin);
});
