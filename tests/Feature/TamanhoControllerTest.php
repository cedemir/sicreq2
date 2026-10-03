<?php

use App\Models\Exemplar;
use App\Models\Tamanho;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $tamanho = Tamanho::factory()->create();

    $this->get(route('tamanhos.index'))
        ->assertOk()
        ->assertSee($tamanho->tamanho_descricao);
});

test('index filters the records by search term', function () {
    Tamanho::factory()->create(['tamanho_descricao' => 'Termo Procurado']);
    Tamanho::factory()->create(['tamanho_descricao' => 'Outro Registro']);

    $this->get(route('tamanhos.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('tamanhos.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'tamanho_descricao' => 'Descrição novo',
        'tamanho_linha' => 20.5,
        'tamanho_coluna' => 20.5,
    ];

    $this->post(route('tamanhos.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tamanhos.index'));

    $this->assertDatabaseHas('tamanho', Arr::only($payload, ['tamanho_descricao', 'tamanho_linha', 'tamanho_coluna']));
});

test('store validates the required fields', function () {
    $this->post(route('tamanhos.store'), [])
        ->assertSessionHasErrors(['tamanho_descricao']);

    $this->assertDatabaseCount('tamanho', 0);
});

test('edit form renders', function () {
    $tamanho = Tamanho::factory()->create();

    $this->get(route('tamanhos.edit', $tamanho))->assertOk();
});

test('update changes the record', function () {
    $tamanho = Tamanho::factory()->create();
    $payload = [
        'tamanho_descricao' => 'Descrição alterado',
        'tamanho_linha' => 30.5,
        'tamanho_coluna' => 30.5,
    ];

    $this->put(route('tamanhos.update', $tamanho), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tamanhos.index'));

    $this->assertDatabaseHas('tamanho', ['tamanho_codigo' => $tamanho->tamanho_codigo, ...Arr::only($payload, ['tamanho_descricao', 'tamanho_linha', 'tamanho_coluna'])]);
});

test('destroy deletes the record', function () {
    $tamanho = Tamanho::factory()->create();

    $this->delete(route('tamanhos.destroy', $tamanho))
        ->assertRedirect(route('tamanhos.index'));

    $this->assertModelMissing($tamanho);
});

test('destroy refuses to delete a record in use', function () {
    $tamanho = Tamanho::factory()->create();
    Exemplar::factory()->create(['tamanho_codigo' => $tamanho->tamanho_codigo]);

    $this->from(route('tamanhos.index'))
        ->delete(route('tamanhos.destroy', $tamanho))
        ->assertRedirect(route('tamanhos.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($tamanho);
});
