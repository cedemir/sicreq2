<?php

use App\Models\Exemplar;
use App\Models\Titulo;
use App\Models\User;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->user = User::factory()->create(['name' => 'Maria Souza']);
    $this->actingAs($this->user);
});

/**
 * Capture the data handed to the PDF template while the report is rendered.
 *
 * @return array<string, mixed>
 */
function capturePdfData(callable $request): array
{
    $data = [];

    View::composer('relatorios.pdf.exemplares', function ($view) use (&$data): void {
        $data = $view->getData();
    });

    $request();

    return $data;
}

test('copies listing links to the report in a new tab', function () {
    $this->get(route('exemplares.index'))
        ->assertOk()
        ->assertSee('href="'.route('exemplares.relatorio').'" target="_blank"', false)
        ->assertSee('Relatório PDF');
});

test('pdf report is returned inline without asking for any parameter', function () {
    $this->travelTo(now()->setDate(2026, 10, 3));
    Exemplar::factory()->create();

    $response = $this->get(route('exemplares.relatorio'));

    $response->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertHeader('content-disposition', 'inline; filename=exemplares-2026-10-03.pdf');

    expect($response->getContent())->toStartWith('%PDF-');
});

test('pdf report includes every copy grouped by title in alphabetical and number order', function () {
    $batman = Titulo::factory()->create(['titulo_descricao' => 'Batman']);
    $tex = Titulo::factory()->create(['titulo_descricao' => 'Tex']);
    foreach ([2, 1] as $numero) {
        Exemplar::factory()->create(['titulo_codigo' => $tex->titulo_codigo, 'exemplar_codigo' => $numero]);
    }
    Exemplar::factory()->create(['titulo_codigo' => $batman->titulo_codigo, 'exemplar_codigo' => 5]);
    Titulo::factory()->create(['titulo_descricao' => 'Sem exemplares']);

    $data = capturePdfData(fn () => $this->get(route('exemplares.relatorio'))->assertOk());

    expect($data['titulos']->pluck('titulo_descricao')->all())->toBe(['Batman', 'Tex'])
        ->and($data['titulos']->last()->exemplares->pluck('exemplar_codigo')->all())->toBe([1, 2])
        ->and($data['geradoPor'])->toBe('Maria Souza');
});

test('pdf template shows the titles, the copies and the totals', function () {
    $tex = Titulo::factory()->create(['titulo_descricao' => 'Tex']);
    Exemplar::factory()->create(['titulo_codigo' => $tex->titulo_codigo, 'exemplar_codigo' => 1, 'exemplar_subtitulo' => 'A Lei do Mais Forte', 'exemplar_numeropagina' => 100]);
    Exemplar::factory()->create(['titulo_codigo' => $tex->titulo_codigo, 'exemplar_codigo' => 2, 'exemplar_subtitulo' => 'O Vale da Morte', 'exemplar_numeropagina' => 120]);

    $this->view('relatorios.pdf.exemplares', [
        'titulos' => Titulo::with('exemplares')->get(),
        'geradoEm' => now(),
        'geradoPor' => 'Maria Souza',
    ])
        ->assertSeeInOrder(['Títulos', '1', 'Exemplares', '2', 'Páginas', '220'])
        ->assertSeeInOrder(['2 exemplares', 'Tex', 'A Lei do Mais Forte', 'O Vale da Morte'])
        ->assertSee('por Maria Souza');
});

test('pdf report works when there are no copies', function () {
    $response = $this->get(route('exemplares.relatorio'))->assertOk();

    expect($response->getContent())->toStartWith('%PDF-');

    $this->view('relatorios.pdf.exemplares', [
        'titulos' => collect(),
        'geradoEm' => now(),
        'geradoPor' => 'Maria Souza',
    ])->assertSee('Nenhum exemplar cadastrado.');
});

test('guests cannot access the report', function () {
    auth()->logout();

    $this->get(route('exemplares.relatorio'))->assertRedirect(route('login'));
});
