<?php

use App\Models\Historia;
use App\Models\HistoriaPersonagem;
use App\Models\Personagem;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $historiaPersonagem = HistoriaPersonagem::factory()->create();

    $this->get(route('historia-personagens.index'))
        ->assertOk()
        ->assertSee($historiaPersonagem->personagem->personagem_descricao);
});

test('index filters the records by search term', function () {
    $matching = HistoriaPersonagem::factory()->create([
        'personagem_codigo' => Personagem::factory()->create(['personagem_descricao' => 'Termo Procurado'])->personagem_codigo,
    ]);
    $other = HistoriaPersonagem::factory()->create([
        'personagem_codigo' => Personagem::factory()->create(['personagem_descricao' => 'Outro Registro'])->personagem_codigo,
    ]);

    $this->get(route('historia-personagens.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('historia-personagens.create'))->assertOk();
});

test('store creates a record', function () {
    $historia = Historia::factory()->create();
    $personagem = Personagem::factory()->create();
    $payload = [
        'titulo_codigo' => $historia->titulo_codigo,
        'exemplar_codigo' => $historia->exemplar_codigo,
        'historia_codigo' => $historia->historia_numero,
        'personagem_codigo' => $personagem->personagem_codigo,
    ];

    $this->post(route('historia-personagens.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('historia-personagens.index'));

    $this->assertDatabaseHas('historiapersonagem', Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'personagem_codigo']));
});

test('store validates the required fields', function () {
    $this->post(route('historia-personagens.store'), [])
        ->assertSessionHasErrors(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'personagem_codigo']);

    $this->assertDatabaseCount('historiapersonagem', 0);
});

test('edit form renders', function () {
    $historiaPersonagem = HistoriaPersonagem::factory()->create();

    $this->get(route('historia-personagens.edit', $historiaPersonagem))->assertOk();
});

test('update changes the record', function () {
    $historiaPersonagem = HistoriaPersonagem::factory()->create();
    $historia = Historia::factory()->create();
    $personagem = Personagem::factory()->create();
    $payload = [
        'titulo_codigo' => $historia->titulo_codigo,
        'exemplar_codigo' => $historia->exemplar_codigo,
        'historia_codigo' => $historia->historia_numero,
        'personagem_codigo' => $personagem->personagem_codigo,
    ];

    $this->put(route('historia-personagens.update', $historiaPersonagem), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('historia-personagens.index'));

    $this->assertDatabaseHas('historiapersonagem', ['id' => $historiaPersonagem->id, ...Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'personagem_codigo'])]);
});

test('destroy deletes the record', function () {
    $historiaPersonagem = HistoriaPersonagem::factory()->create();

    $this->delete(route('historia-personagens.destroy', $historiaPersonagem))
        ->assertRedirect(route('historia-personagens.index'));

    $this->assertModelMissing($historiaPersonagem);
});

test('store rejects a duplicated key', function () {
    $existing = HistoriaPersonagem::factory()->create();

    $payload = array_merge(HistoriaPersonagem::factory()->make()->only(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'personagem_codigo']), [
        'titulo_codigo' => $existing->titulo_codigo,
        'exemplar_codigo' => $existing->exemplar_codigo,
        'historia_codigo' => $existing->historia_codigo,
        'personagem_codigo' => $existing->personagem_codigo,
    ]);

    $this->post(route('historia-personagens.store'), $payload)
        ->assertSessionHasErrors('personagem_codigo');

    $this->assertDatabaseCount('historiapersonagem', 1);
});

test('store rejects a reference to a missing historia', function () {
    $historia = Historia::factory()->create();
    $personagem = Personagem::factory()->create();

    $this->post(route('historia-personagens.store'), [
        'titulo_codigo' => $historia->titulo_codigo,
        'exemplar_codigo' => $historia->exemplar_codigo,
        'historia_codigo' => 999999,
        'personagem_codigo' => $personagem->personagem_codigo,
    ])->assertSessionHasErrors('historia_codigo');
});
