<?php

use App\Models\TipoTitulo;
use App\Models\Titulo;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $tipoTitulo = TipoTitulo::factory()->create();

    $this->get(route('tipos-titulo.index'))
        ->assertOk()
        ->assertSee($tipoTitulo->tipotitulo_descricao);
});

test('index filters the records by search term', function () {
    TipoTitulo::factory()->create(['tipotitulo_descricao' => 'Termo Procurado']);
    TipoTitulo::factory()->create(['tipotitulo_descricao' => 'Outro Registro']);

    $this->get(route('tipos-titulo.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('tipos-titulo.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'tipotitulo_descricao' => 'Descrição novo',
    ];

    $this->post(route('tipos-titulo.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tipos-titulo.index'));

    $this->assertDatabaseHas('tipo_titulo', Arr::only($payload, ['tipotitulo_descricao']));
});

test('store validates the required fields', function () {
    $this->post(route('tipos-titulo.store'), [])
        ->assertSessionHasErrors(['tipotitulo_descricao']);

    $this->assertDatabaseCount('tipo_titulo', 0);
});

test('edit form renders', function () {
    $tipoTitulo = TipoTitulo::factory()->create();

    $this->get(route('tipos-titulo.edit', $tipoTitulo))->assertOk();
});

test('update changes the record', function () {
    $tipoTitulo = TipoTitulo::factory()->create();
    $payload = [
        'tipotitulo_descricao' => 'Descrição alterado',
    ];

    $this->put(route('tipos-titulo.update', $tipoTitulo), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tipos-titulo.index'));

    $this->assertDatabaseHas('tipo_titulo', ['tipotitulo_codigo' => $tipoTitulo->tipotitulo_codigo, ...Arr::only($payload, ['tipotitulo_descricao'])]);
});

test('destroy deletes the record', function () {
    $tipoTitulo = TipoTitulo::factory()->create();

    $this->delete(route('tipos-titulo.destroy', $tipoTitulo))
        ->assertRedirect(route('tipos-titulo.index'));

    $this->assertModelMissing($tipoTitulo);
});

test('destroy refuses to delete a record in use', function () {
    $tipoTitulo = TipoTitulo::factory()->create();
    Titulo::factory()->create(['tipotitulo_codigo' => $tipoTitulo->tipotitulo_codigo]);

    $this->from(route('tipos-titulo.index'))
        ->delete(route('tipos-titulo.destroy', $tipoTitulo))
        ->assertRedirect(route('tipos-titulo.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($tipoTitulo);
});
