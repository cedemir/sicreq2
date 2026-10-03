<?php

use App\Models\Artista;
use App\Models\ArtistaHistoria;
use App\Models\Historia;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $artistaHistoria = ArtistaHistoria::factory()->create();

    $this->get(route('artista-historias.index'))
        ->assertOk()
        ->assertSee($artistaHistoria->artista->artista_nome);
});

test('index filters the records by search term', function () {
    $matching = ArtistaHistoria::factory()->create([
        'artista_codigo' => Artista::factory()->create(['artista_nome' => 'Termo Procurado'])->artista_codigo,
    ]);
    $other = ArtistaHistoria::factory()->create([
        'artista_codigo' => Artista::factory()->create(['artista_nome' => 'Outro Registro'])->artista_codigo,
    ]);

    $this->get(route('artista-historias.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('artista-historias.create'))->assertOk();
});

test('store creates a record', function () {
    $historia = Historia::factory()->create();
    $artista = Artista::factory()->create();
    $payload = [
        'titulo_codigo' => $historia->titulo_codigo,
        'exemplar_codigo' => $historia->exemplar_codigo,
        'historia_codigo' => $historia->historia_numero,
        'artista_codigo' => $artista->artista_codigo,
        'participa_artistahistoria' => 3,
    ];

    $this->post(route('artista-historias.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('artista-historias.index'));

    $this->assertDatabaseHas('artista_historia', Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'artista_codigo', 'participa_artistahistoria']));
});

test('store validates the required fields', function () {
    $this->post(route('artista-historias.store'), [])
        ->assertSessionHasErrors(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'artista_codigo']);

    $this->assertDatabaseCount('artista_historia', 0);
});

test('edit form renders', function () {
    $artistaHistoria = ArtistaHistoria::factory()->create();

    $this->get(route('artista-historias.edit', $artistaHistoria))->assertOk();
});

test('update changes the record', function () {
    $artistaHistoria = ArtistaHistoria::factory()->create();
    $historia = Historia::factory()->create();
    $artista = Artista::factory()->create();
    $payload = [
        'titulo_codigo' => $historia->titulo_codigo,
        'exemplar_codigo' => $historia->exemplar_codigo,
        'historia_codigo' => $historia->historia_numero,
        'artista_codigo' => $artista->artista_codigo,
        'participa_artistahistoria' => 4,
    ];

    $this->put(route('artista-historias.update', $artistaHistoria), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('artista-historias.index'));

    $this->assertDatabaseHas('artista_historia', ['id' => $artistaHistoria->id, ...Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'artista_codigo', 'participa_artistahistoria'])]);
});

test('destroy deletes the record', function () {
    $artistaHistoria = ArtistaHistoria::factory()->create();

    $this->delete(route('artista-historias.destroy', $artistaHistoria))
        ->assertRedirect(route('artista-historias.index'));

    $this->assertModelMissing($artistaHistoria);
});

test('store rejects a duplicated key', function () {
    $existing = ArtistaHistoria::factory()->create();

    $payload = array_merge(ArtistaHistoria::factory()->make()->only(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'artista_codigo', 'participa_artistahistoria']), [
        'titulo_codigo' => $existing->titulo_codigo,
        'exemplar_codigo' => $existing->exemplar_codigo,
        'historia_codigo' => $existing->historia_codigo,
        'artista_codigo' => $existing->artista_codigo,
    ]);

    $this->post(route('artista-historias.store'), $payload)
        ->assertSessionHasErrors('artista_codigo');

    $this->assertDatabaseCount('artista_historia', 1);
});

test('store rejects a reference to a missing historia', function () {
    $historia = Historia::factory()->create();
    $artista = Artista::factory()->create();

    $this->post(route('artista-historias.store'), [
        'titulo_codigo' => $historia->titulo_codigo,
        'exemplar_codigo' => $historia->exemplar_codigo,
        'historia_codigo' => 999999,
        'artista_codigo' => $artista->artista_codigo,
        'participa_artistahistoria' => 3,
    ])->assertSessionHasErrors('historia_codigo');
});
