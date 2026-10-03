<?php

use App\Models\Personagem;
use App\Models\Universo;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $universo = Universo::factory()->create();

    $this->get(route('universos.index'))
        ->assertOk()
        ->assertSee($universo->universo_descricao);
});

test('index filters the records by search term', function () {
    Universo::factory()->create(['universo_descricao' => 'Termo Procurado']);
    Universo::factory()->create(['universo_descricao' => 'Outro Registro']);

    $this->get(route('universos.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('universos.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'universo_descricao' => 'Descrição novo',
    ];

    $this->post(route('universos.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('universos.index'));

    $this->assertDatabaseHas('universo', Arr::only($payload, ['universo_descricao']));
});

test('store validates the required fields', function () {
    $this->post(route('universos.store'), [])
        ->assertSessionHasErrors(['universo_descricao']);

    $this->assertDatabaseCount('universo', 0);
});

test('edit form renders', function () {
    $universo = Universo::factory()->create();

    $this->get(route('universos.edit', $universo))->assertOk();
});

test('update changes the record', function () {
    $universo = Universo::factory()->create();
    $payload = [
        'universo_descricao' => 'Descrição alterado',
    ];

    $this->put(route('universos.update', $universo), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('universos.index'));

    $this->assertDatabaseHas('universo', ['universo_codigo' => $universo->universo_codigo, ...Arr::only($payload, ['universo_descricao'])]);
});

test('destroy deletes the record', function () {
    $universo = Universo::factory()->create();

    $this->delete(route('universos.destroy', $universo))
        ->assertRedirect(route('universos.index'));

    $this->assertModelMissing($universo);
});

test('destroy refuses to delete a record in use', function () {
    $universo = Universo::factory()->create();
    Personagem::factory()->create(['universo_codigo' => $universo->universo_codigo]);

    $this->from(route('universos.index'))
        ->delete(route('universos.destroy', $universo))
        ->assertRedirect(route('universos.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($universo);
});
