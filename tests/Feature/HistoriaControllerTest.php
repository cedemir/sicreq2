<?php

use App\Models\ArtistaHistoria;
use App\Models\Exemplar;
use App\Models\Historia;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $historia = Historia::factory()->create();

    $this->get(route('historias.index'))
        ->assertOk()
        ->assertSee($historia->historia_titulo);
});

test('index filters the records by search term', function () {
    Historia::factory()->create(['historia_titulo' => 'Termo Procurado']);
    Historia::factory()->create(['historia_titulo' => 'Outro Registro']);

    $this->get(route('historias.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('historias.create'))->assertOk();
});

test('store creates a record', function () {
    $exemplar = Exemplar::factory()->create();
    $payload = [
        'titulo_codigo' => $exemplar->titulo_codigo,
        'exemplar_codigo' => $exemplar->exemplar_codigo,
        'historia_numero' => 42,
        'historia_titulo' => 'Título da história novo',
        'historia_resumo' => 'Resumo novo',
        'historia_cor' => true,
        'historia_original' => 'Título original novo',
    ];

    $this->post(route('historias.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('historias.index'));

    $this->assertDatabaseHas('historia', Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'historia_numero', 'historia_titulo', 'historia_resumo', 'historia_cor', 'historia_original']));
});

test('store validates the required fields', function () {
    $this->post(route('historias.store'), [])
        ->assertSessionHasErrors(['titulo_codigo', 'exemplar_codigo', 'historia_numero', 'historia_titulo']);

    $this->assertDatabaseCount('historia', 0);
});

test('edit form renders', function () {
    $historia = Historia::factory()->create();

    $this->get(route('historias.edit', $historia))->assertOk();
});

test('update changes the record', function () {
    $historia = Historia::factory()->create();
    $exemplar = Exemplar::factory()->create();
    $payload = [
        'titulo_codigo' => $exemplar->titulo_codigo,
        'exemplar_codigo' => $exemplar->exemplar_codigo,
        'historia_numero' => 43,
        'historia_titulo' => 'Título da história alterado',
        'historia_resumo' => 'Resumo alterado',
        'historia_cor' => false,
        'historia_original' => 'Título original alterado',
    ];

    $this->put(route('historias.update', $historia), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('historias.index'));

    $this->assertDatabaseHas('historia', ['id' => $historia->id, ...Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'historia_numero', 'historia_titulo', 'historia_resumo', 'historia_cor', 'historia_original'])]);
});

test('destroy deletes the record', function () {
    $historia = Historia::factory()->create();

    $this->delete(route('historias.destroy', $historia))
        ->assertRedirect(route('historias.index'));

    $this->assertModelMissing($historia);
});

test('destroy refuses to delete a record in use', function () {
    $historia = Historia::factory()->create();
    ArtistaHistoria::factory()->create(['titulo_codigo' => $historia->titulo_codigo, 'exemplar_codigo' => $historia->exemplar_codigo, 'historia_codigo' => $historia->historia_numero]);

    $this->from(route('historias.index'))
        ->delete(route('historias.destroy', $historia))
        ->assertRedirect(route('historias.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($historia);
});

test('store rejects a duplicated key', function () {
    $existing = Historia::factory()->create();

    $payload = array_merge(Historia::factory()->make()->only(['titulo_codigo', 'exemplar_codigo', 'historia_numero', 'historia_titulo', 'historia_resumo', 'historia_cor', 'historia_original']), [
        'titulo_codigo' => $existing->titulo_codigo,
        'exemplar_codigo' => $existing->exemplar_codigo,
        'historia_numero' => $existing->historia_numero,
    ]);

    $this->post(route('historias.store'), $payload)
        ->assertSessionHasErrors('historia_numero');

    $this->assertDatabaseCount('historia', 1);
});

test('store rejects a reference to a missing exemplar', function () {
    $exemplar = Exemplar::factory()->create();

    $this->post(route('historias.store'), [
        'titulo_codigo' => $exemplar->titulo_codigo,
        'exemplar_codigo' => 999999,
        'historia_numero' => 42,
        'historia_titulo' => 'Título da história novo',
        'historia_resumo' => 'Resumo novo',
        'historia_cor' => true,
        'historia_original' => 'Título original novo',
    ])->assertSessionHasErrors('exemplar_codigo');
});

test('store accepts a summary longer than 255 characters', function () {
    $exemplar = Exemplar::factory()->create();
    $resumo = trim(str_repeat('Resumo longo da história. ', 40));

    $this->post(route('historias.store'), [
        'titulo_codigo' => $exemplar->titulo_codigo,
        'exemplar_codigo' => $exemplar->exemplar_codigo,
        'historia_numero' => 1,
        'historia_titulo' => 'História com resumo longo',
        'historia_resumo' => $resumo,
    ])->assertSessionHasNoErrors();

    expect(Historia::sole()->historia_resumo)->toBe($resumo);
});

test('validation messages are shown in Portuguese with the field labels', function () {
    $this->post(route('historias.store'), ['titulo_codigo' => 999999])
        ->assertSessionHasErrors([
            'historia_titulo' => 'O campo título da história é obrigatório.',
            'historia_numero' => 'O campo história (número) é obrigatório.',
            'titulo_codigo' => 'O valor selecionado para título é inválido.',
        ]);
});
