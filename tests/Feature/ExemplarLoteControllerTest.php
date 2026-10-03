<?php

use App\Models\Armazenagem;
use App\Models\Exemplar;
use App\Models\Tamanho;
use App\Models\Titulo;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('batch form renders', function () {
    $titulo = Titulo::factory()->create();

    $this->get(route('exemplares.lote.create'))
        ->assertOk()
        ->assertSee($titulo->titulo_descricao);
});

test('generates every copy from the first to the last number with the given data', function () {
    $titulo = Titulo::factory()->create();
    $armazenagem = Armazenagem::factory()->create();
    $tamanho = Tamanho::factory()->create();

    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_inicial' => 10,
        'exemplar_final' => 14,
        'armazenagem_codigo' => $armazenagem->armazenagem_codigo,
        'tamanho_codigo' => $tamanho->tamanho_codigo,
        'exemplar_numeropagina' => 52,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('exemplares.index', ['busca' => $titulo->titulo_descricao]))
        ->assertSessionHas('success', "5 exemplares gerados para {$titulo->titulo_descricao}.");

    $exemplares = Exemplar::whereBelongsTo($titulo)->orderBy('exemplar_codigo')->get();

    expect($exemplares->pluck('exemplar_codigo')->all())->toBe([10, 11, 12, 13, 14]);

    $exemplares->each(fn (Exemplar $exemplar) => expect($exemplar)
        ->armazenagem_codigo->toBe($armazenagem->armazenagem_codigo)
        ->tamanho_codigo->toBe($tamanho->tamanho_codigo)
        ->exemplar_numeropagina->toBe(52));
});

test('generates a single copy when the first and last numbers are equal', function () {
    $titulo = Titulo::factory()->create();

    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_inicial' => 7,
        'exemplar_final' => 7,
    ])->assertSessionHas('success', "1 exemplar gerado para {$titulo->titulo_descricao}.");

    expect(Exemplar::whereBelongsTo($titulo)->pluck('exemplar_codigo')->all())->toBe([7]);
});

test('keeps existing copies untouched and reports them', function () {
    $titulo = Titulo::factory()->create();
    $existente = Exemplar::factory()->create([
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_codigo' => 2,
        'exemplar_numeropagina' => 100,
    ]);

    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_inicial' => 1,
        'exemplar_final' => 3,
        'exemplar_numeropagina' => 36,
    ])->assertSessionHas('success', "2 exemplares gerados para {$titulo->titulo_descricao}. Já existiam e foram mantidos: nº 2.");

    expect(Exemplar::whereBelongsTo($titulo)->count())->toBe(3)
        ->and($existente->refresh()->exemplar_numeropagina)->toBe(100);
});

test('only checks existing copies of the same title', function () {
    $titulo = Titulo::factory()->create();
    Exemplar::factory()->create(['exemplar_codigo' => 1]);

    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_inicial' => 1,
        'exemplar_final' => 2,
    ])->assertSessionHasNoErrors();

    expect(Exemplar::whereBelongsTo($titulo)->count())->toBe(2);
});

test('validates the required fields', function () {
    $this->post(route('exemplares.lote.store'), [])
        ->assertSessionHasErrors(['titulo_codigo', 'exemplar_inicial', 'exemplar_final']);
});

test('rejects a last number lower than the first one', function () {
    $titulo = Titulo::factory()->create();

    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_inicial' => 10,
        'exemplar_final' => 5,
    ])->assertSessionHasErrors('exemplar_final');

    $this->assertDatabaseCount('exemplar', 0);
});

test('rejects generating more copies than the limit at once', function () {
    $titulo = Titulo::factory()->create();

    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => $titulo->titulo_codigo,
        'exemplar_inicial' => 1,
        'exemplar_final' => 1001,
    ])->assertSessionHasErrors(['exemplar_final' => 'É possível gerar no máximo 1000 exemplares por vez.']);

    $this->assertDatabaseCount('exemplar', 0);
});

test('rejects a title, storage or size that does not exist', function () {
    $this->post(route('exemplares.lote.store'), [
        'titulo_codigo' => 999999,
        'exemplar_inicial' => 1,
        'exemplar_final' => 2,
        'armazenagem_codigo' => 999999,
        'tamanho_codigo' => 999999,
    ])->assertSessionHasErrors(['titulo_codigo', 'armazenagem_codigo', 'tamanho_codigo']);
});

test('guests cannot generate copies', function () {
    auth()->logout();

    $this->get(route('exemplares.lote.create'))->assertRedirect(route('login'));
    $this->post(route('exemplares.lote.store'), [])->assertRedirect(route('login'));
});
