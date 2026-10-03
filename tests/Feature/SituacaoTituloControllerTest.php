<?php

use App\Models\SituacaoTitulo;
use App\Models\Titulo;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $situacaoTitulo = SituacaoTitulo::factory()->create();

    $this->get(route('situacoes-titulo.index'))
        ->assertOk()
        ->assertSee($situacaoTitulo->situacao_descricao);
});

test('index filters the records by search term', function () {
    SituacaoTitulo::factory()->create(['situacao_descricao' => 'Termo Procurado']);
    SituacaoTitulo::factory()->create(['situacao_descricao' => 'Outro Registro']);

    $this->get(route('situacoes-titulo.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('situacoes-titulo.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'situacao_descricao' => 'Descrição novo',
    ];

    $this->post(route('situacoes-titulo.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('situacoes-titulo.index'));

    $this->assertDatabaseHas('situacao_titulo', Arr::only($payload, ['situacao_descricao']));
});

test('store validates the required fields', function () {
    $this->post(route('situacoes-titulo.store'), [])
        ->assertSessionHasErrors(['situacao_descricao']);

    $this->assertDatabaseCount('situacao_titulo', 0);
});

test('edit form renders', function () {
    $situacaoTitulo = SituacaoTitulo::factory()->create();

    $this->get(route('situacoes-titulo.edit', $situacaoTitulo))->assertOk();
});

test('update changes the record', function () {
    $situacaoTitulo = SituacaoTitulo::factory()->create();
    $payload = [
        'situacao_descricao' => 'Descrição alterado',
    ];

    $this->put(route('situacoes-titulo.update', $situacaoTitulo), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('situacoes-titulo.index'));

    $this->assertDatabaseHas('situacao_titulo', ['situacao_codigo' => $situacaoTitulo->situacao_codigo, ...Arr::only($payload, ['situacao_descricao'])]);
});

test('destroy deletes the record', function () {
    $situacaoTitulo = SituacaoTitulo::factory()->create();

    $this->delete(route('situacoes-titulo.destroy', $situacaoTitulo))
        ->assertRedirect(route('situacoes-titulo.index'));

    $this->assertModelMissing($situacaoTitulo);
});

test('destroy refuses to delete a record in use', function () {
    $situacaoTitulo = SituacaoTitulo::factory()->create();
    Titulo::factory()->create(['situacao_codigo' => $situacaoTitulo->situacao_codigo]);

    $this->from(route('situacoes-titulo.index'))
        ->delete(route('situacoes-titulo.destroy', $situacaoTitulo))
        ->assertRedirect(route('situacoes-titulo.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($situacaoTitulo);
});
