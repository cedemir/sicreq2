<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

test('login form renders for guests', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Acesse sua conta');
});

test('authenticated users are redirected away from the login form', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));
});

test('users can log in with valid credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('users are sent to the page they originally requested after logging in', function () {
    $user = User::factory()->create();

    $this->get(route('titulos.index'))->assertRedirect(route('login'));

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('titulos.index'));
});

test('users cannot log in with a wrong password', function () {
    $user = User::factory()->create();

    $this->from(route('login'))
        ->post(route('login'), ['email' => $user->email, 'password' => 'senha-errada'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['email' => 'E-mail ou senha incorretos.']);

    $this->assertGuest();
});

test('login is locked after too many failed attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login'), ['email' => $user->email, 'password' => 'senha-errada']);
    }

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(session('errors')->first('email'))->toStartWith('Muitas tentativas de acesso.');

    RateLimiter::clear(strtolower($user->email).'|127.0.0.1');
});

test('users can log out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('guests cannot access the protected pages', function () {
    $this->get(route('titulos.index'))->assertRedirect(route('login'));
    $this->get(route('usuarios.index'))->assertRedirect(route('login'));
});
