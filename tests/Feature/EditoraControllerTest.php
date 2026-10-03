<?php

use App\Models\Editora;
use App\Models\Titulo;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $editora = Editora::factory()->create();

    $this->get(route('editoras.index'))
        ->assertOk()
        ->assertSee($editora->editora_descricao);
});

test('index filters the records by search term', function () {
    Editora::factory()->create(['editora_descricao' => 'Termo Procurado']);
    Editora::factory()->create(['editora_descricao' => 'Outro Registro']);

    $this->get(route('editoras.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('editoras.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'editora_descricao' => 'Nome novo',
    ];

    $this->post(route('editoras.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('editoras.index'));

    $this->assertDatabaseHas('editora', Arr::only($payload, ['editora_descricao']));
});

test('store validates the required fields', function () {
    $this->post(route('editoras.store'), [])
        ->assertSessionHasErrors(['editora_descricao']);

    $this->assertDatabaseCount('editora', 0);
});

test('edit form renders', function () {
    $editora = Editora::factory()->create();

    $this->get(route('editoras.edit', $editora))->assertOk();
});

test('update changes the record', function () {
    $editora = Editora::factory()->create();
    $payload = [
        'editora_descricao' => 'Nome alterado',
    ];

    $this->put(route('editoras.update', $editora), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('editoras.index'));

    $this->assertDatabaseHas('editora', ['editora_codigo' => $editora->editora_codigo, ...Arr::only($payload, ['editora_descricao'])]);
});

test('destroy deletes the record', function () {
    $editora = Editora::factory()->create();

    $this->delete(route('editoras.destroy', $editora))
        ->assertRedirect(route('editoras.index'));

    $this->assertModelMissing($editora);
});

test('destroy refuses to delete a record in use', function () {
    $editora = Editora::factory()->create();
    Titulo::factory()->create(['editora_codigo' => $editora->editora_codigo]);

    $this->from(route('editoras.index'))
        ->delete(route('editoras.destroy', $editora))
        ->assertRedirect(route('editoras.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($editora);
});
