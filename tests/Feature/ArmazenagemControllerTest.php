<?php

use App\Models\Armazenagem;
use App\Models\Exemplar;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $armazenagem = Armazenagem::factory()->create();

    $this->get(route('armazenagens.index'))
        ->assertOk()
        ->assertSee($armazenagem->armazenagem_descricao);
});

test('index filters the records by search term', function () {
    Armazenagem::factory()->create(['armazenagem_descricao' => 'Termo Procurado']);
    Armazenagem::factory()->create(['armazenagem_descricao' => 'Outro Registro']);

    $this->get(route('armazenagens.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('armazenagens.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'armazenagem_descricao' => 'Descrição novo',
    ];

    $this->post(route('armazenagens.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('armazenagens.index'));

    $this->assertDatabaseHas('armazenagem', Arr::only($payload, ['armazenagem_descricao']));
});

test('store validates the required fields', function () {
    $this->post(route('armazenagens.store'), [])
        ->assertSessionHasErrors(['armazenagem_descricao']);

    $this->assertDatabaseCount('armazenagem', 0);
});

test('edit form renders', function () {
    $armazenagem = Armazenagem::factory()->create();

    $this->get(route('armazenagens.edit', $armazenagem))->assertOk();
});

test('update changes the record', function () {
    $armazenagem = Armazenagem::factory()->create();
    $payload = [
        'armazenagem_descricao' => 'Descrição alterado',
    ];

    $this->put(route('armazenagens.update', $armazenagem), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('armazenagens.index'));

    $this->assertDatabaseHas('armazenagem', ['armazenagem_codigo' => $armazenagem->armazenagem_codigo, ...Arr::only($payload, ['armazenagem_descricao'])]);
});

test('destroy deletes the record', function () {
    $armazenagem = Armazenagem::factory()->create();

    $this->delete(route('armazenagens.destroy', $armazenagem))
        ->assertRedirect(route('armazenagens.index'));

    $this->assertModelMissing($armazenagem);
});

test('destroy refuses to delete a record in use', function () {
    $armazenagem = Armazenagem::factory()->create();
    Exemplar::factory()->create(['armazenagem_codigo' => $armazenagem->armazenagem_codigo]);

    $this->from(route('armazenagens.index'))
        ->delete(route('armazenagens.destroy', $armazenagem))
        ->assertRedirect(route('armazenagens.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($armazenagem);
});
