<?php

use App\Models\Armazenagem;
use App\Models\Exemplar;
use App\Models\Historia;
use App\Models\Tamanho;
use App\Models\Titulo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $exemplar = Exemplar::factory()->create();

    $this->get(route('exemplares.index'))
        ->assertOk()
        ->assertSee($exemplar->exemplar_subtitulo);
});

test('index filters the records by search term', function () {
    Exemplar::factory()->create(['exemplar_subtitulo' => 'Termo Procurado']);
    Exemplar::factory()->create(['exemplar_subtitulo' => 'Outro Registro']);

    $this->get(route('exemplares.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('exemplares.create'))->assertOk();
});

test('store creates a record', function () {
    $titulo = Titulo::factory()->create();
    $armazenagem = Armazenagem::factory()->create();
    $tamanho = Tamanho::factory()->create();
    $payload = [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_codigo' => 42,
        'exemplar_subtitulo' => 'Subtítulo novo',
        'exemplar_data' => '2024-05-10',
        'armazenagem_codigo' => $armazenagem->armazenagem_codigo,
        'tamanho_codigo' => $tamanho->tamanho_codigo,
        'exemplar_situacao' => 3,
        'exemplar_numeropagina' => 3,
    ];

    $this->post(route('exemplares.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('exemplares.index'));

    $this->assertDatabaseHas('exemplar', Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'exemplar_subtitulo', 'armazenagem_codigo', 'tamanho_codigo', 'exemplar_situacao', 'exemplar_numeropagina']));
});

test('store validates the required fields', function () {
    $this->post(route('exemplares.store'), [])
        ->assertSessionHasErrors(['titulo_codigo', 'exemplar_codigo']);

    $this->assertDatabaseCount('exemplar', 0);
});

test('edit form renders', function () {
    $exemplar = Exemplar::factory()->create();

    $this->get(route('exemplares.edit', $exemplar))->assertOk();
});

test('update changes the record', function () {
    $exemplar = Exemplar::factory()->create();
    $titulo = Titulo::factory()->create();
    $armazenagem = Armazenagem::factory()->create();
    $tamanho = Tamanho::factory()->create();
    $payload = [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_codigo' => 43,
        'exemplar_subtitulo' => 'Subtítulo alterado',
        'exemplar_data' => '2024-05-10',
        'armazenagem_codigo' => $armazenagem->armazenagem_codigo,
        'tamanho_codigo' => $tamanho->tamanho_codigo,
        'exemplar_situacao' => 4,
        'exemplar_numeropagina' => 4,
    ];

    $this->put(route('exemplares.update', $exemplar), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('exemplares.index'));

    $this->assertDatabaseHas('exemplar', ['id' => $exemplar->id, ...Arr::only($payload, ['titulo_codigo', 'exemplar_codigo', 'exemplar_subtitulo', 'armazenagem_codigo', 'tamanho_codigo', 'exemplar_situacao', 'exemplar_numeropagina'])]);
});

test('destroy deletes the record', function () {
    $exemplar = Exemplar::factory()->create();

    $this->delete(route('exemplares.destroy', $exemplar))
        ->assertRedirect(route('exemplares.index'));

    $this->assertModelMissing($exemplar);
});

test('destroy refuses to delete a record in use', function () {
    $exemplar = Exemplar::factory()->create();
    Historia::factory()->create(['titulo_codigo' => $exemplar->titulo_codigo, 'exemplar_codigo' => $exemplar->exemplar_codigo]);

    $this->from(route('exemplares.index'))
        ->delete(route('exemplares.destroy', $exemplar))
        ->assertRedirect(route('exemplares.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($exemplar);
});

test('store rejects a duplicated key', function () {
    $existing = Exemplar::factory()->create();

    $payload = array_merge(Exemplar::factory()->make()->only(['titulo_codigo', 'exemplar_codigo', 'exemplar_subtitulo', 'armazenagem_codigo', 'tamanho_codigo', 'exemplar_situacao', 'exemplar_numeropagina']), [
        'titulo_codigo' => $existing->titulo_codigo,
        'exemplar_codigo' => $existing->exemplar_codigo,
    ]);

    $this->post(route('exemplares.store'), $payload)
        ->assertSessionHasErrors('exemplar_codigo');

    $this->assertDatabaseCount('exemplar', 1);
});

test('store uploads the image', function () {
    Storage::fake('public');
    $titulo = Titulo::factory()->create();
    $armazenagem = Armazenagem::factory()->create();
    $tamanho = Tamanho::factory()->create();

    $this->post(route('exemplares.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_codigo' => 42,
        'exemplar_subtitulo' => 'Subtítulo novo',
        'exemplar_data' => '2024-05-10',
        'armazenagem_codigo' => $armazenagem->armazenagem_codigo,
        'tamanho_codigo' => $tamanho->tamanho_codigo,
        'exemplar_situacao' => 3,
        'exemplar_numeropagina' => 3,
        'exemplar_capa' => UploadedFile::fake()->image('imagem.jpg'),
    ])->assertSessionHasNoErrors();

    $path = Exemplar::sole()->exemplar_capa;

    expect($path)->toStartWith('capas/');
    Storage::disk('public')->assertExists($path);
});

test('update replaces the previous image', function () {
    Storage::fake('public');
    $old = UploadedFile::fake()->image('antiga.jpg')->store('capas', 'public');
    $exemplar = Exemplar::factory()->create(['exemplar_capa' => $old]);

    $this->put(route('exemplares.update', $exemplar), [
        ...$exemplar->only(['titulo_codigo', 'exemplar_codigo', 'exemplar_subtitulo', 'armazenagem_codigo', 'tamanho_codigo', 'exemplar_situacao', 'exemplar_numeropagina']),
        'exemplar_capa' => UploadedFile::fake()->image('nova.jpg'),
    ])->assertSessionHasNoErrors();

    Storage::disk('public')->assertMissing($old);
    Storage::disk('public')->assertExists($exemplar->refresh()->exemplar_capa);
});
