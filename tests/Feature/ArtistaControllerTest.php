<?php

use App\Models\Artista;
use App\Models\PersonagemCriador;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('index lists the records', function () {
    $artista = Artista::factory()->create();

    $this->get(route('artistas.index'))
        ->assertOk()
        ->assertSee($artista->artista_nome);
});

test('index filters the records by search term', function () {
    Artista::factory()->create(['artista_nome' => 'Termo Procurado']);
    Artista::factory()->create(['artista_nome' => 'Outro Registro']);

    $this->get(route('artistas.index', ['busca' => 'procurado']))
        ->assertOk()
        ->assertSee('Termo Procurado')
        ->assertDontSee('Outro Registro');
});

test('create form renders', function () {
    $this->get(route('artistas.create'))->assertOk();
});

test('store creates a record', function () {
    $payload = [
        'artista_nome' => 'Nome novo',
        'artista_historico' => 'Histórico novo',
    ];

    $this->post(route('artistas.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('artistas.index'));

    $this->assertDatabaseHas('artista', Arr::only($payload, ['artista_nome', 'artista_historico']));
});

test('store validates the required fields', function () {
    $this->post(route('artistas.store'), [])
        ->assertSessionHasErrors(['artista_nome']);

    $this->assertDatabaseCount('artista', 0);
});

test('edit form renders', function () {
    $artista = Artista::factory()->create();

    $this->get(route('artistas.edit', $artista))->assertOk();
});

test('update changes the record', function () {
    $artista = Artista::factory()->create();
    $payload = [
        'artista_nome' => 'Nome alterado',
        'artista_historico' => 'Histórico alterado',
    ];

    $this->put(route('artistas.update', $artista), $payload)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('artistas.index'));

    $this->assertDatabaseHas('artista', ['artista_codigo' => $artista->artista_codigo, ...Arr::only($payload, ['artista_nome', 'artista_historico'])]);
});

test('destroy deletes the record', function () {
    $artista = Artista::factory()->create();

    $this->delete(route('artistas.destroy', $artista))
        ->assertRedirect(route('artistas.index'));

    $this->assertModelMissing($artista);
});

test('destroy refuses to delete a record in use', function () {
    $artista = Artista::factory()->create();
    PersonagemCriador::factory()->create(['artista_codigo' => $artista->artista_codigo]);

    $this->from(route('artistas.index'))
        ->delete(route('artistas.destroy', $artista))
        ->assertRedirect(route('artistas.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($artista);
});

test('store uploads the image', function () {
    Storage::fake('public');

    $this->post(route('artistas.store'), [
        'artista_nome' => 'Nome novo',
        'artista_historico' => 'Histórico novo',
        'artista_foto' => UploadedFile::fake()->image('imagem.jpg'),
    ])->assertSessionHasNoErrors();

    $path = Artista::sole()->artista_foto;

    expect($path)->toStartWith('artistas/');
    Storage::disk('public')->assertExists($path);
});

test('update replaces the previous image', function () {
    Storage::fake('public');
    $old = UploadedFile::fake()->image('antiga.jpg')->store('artistas', 'public');
    $artista = Artista::factory()->create(['artista_foto' => $old]);

    $this->put(route('artistas.update', $artista), [
        ...$artista->only(['artista_nome', 'artista_historico']),
        'artista_foto' => UploadedFile::fake()->image('nova.jpg'),
    ])->assertSessionHasNoErrors();

    Storage::disk('public')->assertMissing($old);
    Storage::disk('public')->assertExists($artista->refresh()->artista_foto);
});
