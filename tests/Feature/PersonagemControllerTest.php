<?php

use App\Models\ImagemPersonagem;
use App\Models\Personagem;
use App\Models\Universo;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $personagem = Personagem::factory()->create();

    $this->get(route('personagens.index'))
        ->assertOk()
        ->assertSee($personagem->personagem_descricao);
});

test('index filters the records by search term', function () {
    Personagem::factory()->create(['personagem_descricao' => 'Termo Procurado']);
    Personagem::factory()->create(['personagem_descricao' => 'Outro Registro']);

    $this->get(route('personagens.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('personagens.create'))->assertOk();
});

test('store creates a record', function () {
    $universo = Universo::factory()->create();
    $payload = [
        'personagem_descricao' => 'Nome novo',
        'universo_codigo' => $universo->universo_codigo,
        'personagem_origem' => 'Origem novo',
    ];

    $this->post(route('personagens.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('personagens.index'));

    $this->assertDatabaseHas('personagem', Arr::only($payload, ['personagem_descricao', 'universo_codigo', 'personagem_origem']));
});

test('store validates the required fields', function () {
    $this->post(route('personagens.store'), [])
        ->assertSessionHasErrors(['personagem_descricao', 'universo_codigo']);

    $this->assertDatabaseCount('personagem', 0);
});

test('edit form renders', function () {
    $personagem = Personagem::factory()->create();

    $this->get(route('personagens.edit', $personagem))->assertOk();
});

test('update changes the record', function () {
    $personagem = Personagem::factory()->create();
    $universo = Universo::factory()->create();
    $payload = [
        'personagem_descricao' => 'Nome alterado',
        'universo_codigo' => $universo->universo_codigo,
        'personagem_origem' => 'Origem alterado',
    ];

    $this->put(route('personagens.update', $personagem), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('personagens.index'));

    $this->assertDatabaseHas('personagem', ['personagem_codigo' => $personagem->personagem_codigo, ...Arr::only($payload, ['personagem_descricao', 'universo_codigo', 'personagem_origem'])]);
});

test('destroy deletes the record', function () {
    $personagem = Personagem::factory()->create();

    $this->delete(route('personagens.destroy', $personagem))
        ->assertRedirect(route('personagens.index'));

    $this->assertModelMissing($personagem);
});

test('destroy refuses to delete a record in use', function () {
    $personagem = Personagem::factory()->create();
    ImagemPersonagem::factory()->create(['personagem_codigo' => $personagem->personagem_codigo]);

    $this->from(route('personagens.index'))
        ->delete(route('personagens.destroy', $personagem))
        ->assertRedirect(route('personagens.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($personagem);
});

test('store accepts an origin longer than 255 characters', function () {
    $universo = Universo::factory()->create();
    $origem = trim(str_repeat('Origem detalhada do personagem. ', 40));

    $this->post(route('personagens.store'), [
        'personagem_descricao' => 'Personagem com origem longa',
        'universo_codigo' => $universo->universo_codigo,
        'personagem_origem' => $origem,
    ])->assertSessionHasNoErrors();

    expect(Personagem::sole()->personagem_origem)->toBe($origem);
});
