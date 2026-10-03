<?php

use App\Models\ImagemPersonagem;
use App\Models\Personagem;
use App\Models\User;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $imagemPersonagem = ImagemPersonagem::factory()->create();

    $this->get(route('imagens-personagem.index'))
        ->assertOk()
        ->assertSee($imagemPersonagem->imagem_descricao);
});

test('index filters the records by search term', function () {
    ImagemPersonagem::factory()->create(['imagem_descricao' => 'Termo Procurado']);
    ImagemPersonagem::factory()->create(['imagem_descricao' => 'Outro Registro']);

    $this->get(route('imagens-personagem.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('imagens-personagem.create'))->assertOk();
});

test('store creates a record', function () {
    $personagem = Personagem::factory()->create();
    $payload = [
        'personagem_codigo' => $personagem->personagem_codigo,
        'imagem_codigo' => 42,
        'imagem_descricao' => 'Descrição novo',
    ];

    $this->post(route('imagens-personagem.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('imagens-personagem.index'));

    $this->assertDatabaseHas('imagempersonagem', Arr::only($payload, ['personagem_codigo', 'imagem_codigo', 'imagem_descricao']));
});

test('store validates the required fields', function () {
    $this->post(route('imagens-personagem.store'), [])
        ->assertSessionHasErrors(['personagem_codigo']);

    $this->assertDatabaseCount('imagempersonagem', 0);
});

test('edit form renders', function () {
    $imagemPersonagem = ImagemPersonagem::factory()->create();

    $this->get(route('imagens-personagem.edit', $imagemPersonagem))->assertOk();
});

test('update changes the record', function () {
    $imagemPersonagem = ImagemPersonagem::factory()->create();
    $personagem = Personagem::factory()->create();
    $payload = [
        'personagem_codigo' => $personagem->personagem_codigo,
        'imagem_codigo' => 43,
        'imagem_descricao' => 'Descrição alterado',
    ];

    $this->put(route('imagens-personagem.update', $imagemPersonagem), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('imagens-personagem.index'));

    $this->assertDatabaseHas('imagempersonagem', ['id' => $imagemPersonagem->id, ...Arr::only($payload, ['personagem_codigo', 'imagem_codigo', 'imagem_descricao'])]);
});

test('destroy deletes the record', function () {
    $imagemPersonagem = ImagemPersonagem::factory()->create();

    $this->delete(route('imagens-personagem.destroy', $imagemPersonagem))
        ->assertRedirect(route('imagens-personagem.index'));

    $this->assertModelMissing($imagemPersonagem);
});
