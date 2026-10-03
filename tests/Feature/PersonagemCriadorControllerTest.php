<?php

use App\Models\Artista;
use App\Models\Personagem;
use App\Models\PersonagemCriador;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $personagemCriador = PersonagemCriador::factory()->create();

    $this->get(route('personagem-criadores.index'))
        ->assertOk()
        ->assertSee($personagemCriador->artista->artista_nome);
});

test('index filters the records by search term', function () {
    $matching = PersonagemCriador::factory()->create([
        'personagem_codigo' => Personagem::factory()->create(['personagem_descricao' => 'Termo Procurado'])->personagem_codigo,
    ]);
    $other = PersonagemCriador::factory()->create([
        'personagem_codigo' => Personagem::factory()->create(['personagem_descricao' => 'Outro Registro'])->personagem_codigo,
    ]);

    $this->get(route('personagem-criadores.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('personagem-criadores.create'))->assertOk();
});

test('store creates a record', function () {
    $personagem = Personagem::factory()->create();
    $artista = Artista::factory()->create();
    $payload = [
        'personagem_codigo' => $personagem->personagem_codigo,
        'artista_codigo' => $artista->artista_codigo,
    ];

    $this->post(route('personagem-criadores.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('personagem-criadores.index'));

    $this->assertDatabaseHas('personagem_criador', Arr::only($payload, ['personagem_codigo', 'artista_codigo']));
});

test('store validates the required fields', function () {
    $this->post(route('personagem-criadores.store'), [])
        ->assertSessionHasErrors(['personagem_codigo', 'artista_codigo']);

    $this->assertDatabaseCount('personagem_criador', 0);
});

test('edit form renders', function () {
    $personagemCriador = PersonagemCriador::factory()->create();

    $this->get(route('personagem-criadores.edit', $personagemCriador))->assertOk();
});

test('update changes the record', function () {
    $personagemCriador = PersonagemCriador::factory()->create();
    $personagem = Personagem::factory()->create();
    $artista = Artista::factory()->create();
    $payload = [
        'personagem_codigo' => $personagem->personagem_codigo,
        'artista_codigo' => $artista->artista_codigo,
    ];

    $this->put(route('personagem-criadores.update', $personagemCriador), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('personagem-criadores.index'));

    $this->assertDatabaseHas('personagem_criador', ['id' => $personagemCriador->id, ...Arr::only($payload, ['personagem_codigo', 'artista_codigo'])]);
});

test('destroy deletes the record', function () {
    $personagemCriador = PersonagemCriador::factory()->create();

    $this->delete(route('personagem-criadores.destroy', $personagemCriador))
        ->assertRedirect(route('personagem-criadores.index'));

    $this->assertModelMissing($personagemCriador);
});

test('store rejects a duplicated key', function () {
    $existing = PersonagemCriador::factory()->create();

    $payload = array_merge(PersonagemCriador::factory()->make()->only(['personagem_codigo', 'artista_codigo']), [
        'personagem_codigo' => $existing->personagem_codigo,
        'artista_codigo' => $existing->artista_codigo,
    ]);

    $this->post(route('personagem-criadores.store'), $payload)
        ->assertSessionHasErrors('artista_codigo');

    $this->assertDatabaseCount('personagem_criador', 1);
});
