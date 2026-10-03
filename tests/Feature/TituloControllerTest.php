<?php

use App\Models\Editora;
use App\Models\Exemplar;
use App\Models\SituacaoTitulo;
use App\Models\TipoTitulo;
use App\Models\Titulo;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $titulo = Titulo::factory()->create();

    $this->get(route('titulos.index'))
        ->assertOk()
        ->assertSee($titulo->titulo_descricao);
});

test('index filters the records by search term', function () {
    Titulo::factory()->create(['titulo_descricao' => 'Termo Procurado']);
    Titulo::factory()->create(['titulo_descricao' => 'Outro Registro']);

    $this->get(route('titulos.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('titulos.create'))->assertOk();
});

test('store creates a record', function () {
    $editora = Editora::factory()->create();
    $tipoTitulo = TipoTitulo::factory()->create();
    $situacaoTitulo = SituacaoTitulo::factory()->create();
    $payload = [
        'titulo_descricao' => 'Nome novo',
        'editora_codigo' => $editora->editora_codigo,
        'tipotitulo_codigo' => $tipoTitulo->tipotitulo_codigo,
        'situacao_codigo' => $situacaoTitulo->situacao_codigo,
        'titulo_periodicidade' => 3,
        'titulo_origem' => 'Origem novo',
    ];

    $this->post(route('titulos.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('titulos.index'));

    $this->assertDatabaseHas('titulo', Arr::only($payload, ['titulo_descricao', 'editora_codigo', 'tipotitulo_codigo', 'situacao_codigo', 'titulo_periodicidade', 'titulo_origem']));
});

test('store validates the required fields', function () {
    $this->post(route('titulos.store'), [])
        ->assertSessionHasErrors(['titulo_descricao', 'editora_codigo', 'tipotitulo_codigo', 'situacao_codigo']);

    $this->assertDatabaseCount('titulo', 0);
});

test('edit form renders', function () {
    $titulo = Titulo::factory()->create();

    $this->get(route('titulos.edit', $titulo))->assertOk();
});

test('update changes the record', function () {
    $titulo = Titulo::factory()->create();
    $editora = Editora::factory()->create();
    $tipoTitulo = TipoTitulo::factory()->create();
    $situacaoTitulo = SituacaoTitulo::factory()->create();
    $payload = [
        'titulo_descricao' => 'Nome alterado',
        'editora_codigo' => $editora->editora_codigo,
        'tipotitulo_codigo' => $tipoTitulo->tipotitulo_codigo,
        'situacao_codigo' => $situacaoTitulo->situacao_codigo,
        'titulo_periodicidade' => 4,
        'titulo_origem' => 'Origem alterado',
    ];

    $this->put(route('titulos.update', $titulo), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('titulos.index'));

    $this->assertDatabaseHas('titulo', ['titulo_codigo' => $titulo->titulo_codigo, ...Arr::only($payload, ['titulo_descricao', 'editora_codigo', 'tipotitulo_codigo', 'situacao_codigo', 'titulo_periodicidade', 'titulo_origem'])]);
});

test('destroy deletes the record', function () {
    $titulo = Titulo::factory()->create();

    $this->delete(route('titulos.destroy', $titulo))
        ->assertRedirect(route('titulos.index'));

    $this->assertModelMissing($titulo);
});

test('destroy refuses to delete a record in use', function () {
    $titulo = Titulo::factory()->create();
    Exemplar::factory()->create(['titulo_codigo' => $titulo->titulo_codigo]);

    $this->from(route('titulos.index'))
        ->delete(route('titulos.destroy', $titulo))
        ->assertRedirect(route('titulos.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($titulo);
});
