<?php

use App\Models\Titulo;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('dashboard shows the record count of each table', function () {
    Titulo::factory(3)->create();

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Títulos', '3']);
});

test('every menu entry points to a working listing', function (string $route) {
    $this->get(route("{$route}.index"))->assertOk();
})->with(array_merge(...array_map(array_keys(...), array_values(require __DIR__.'/../../config/menu.php'))));
