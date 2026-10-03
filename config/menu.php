<?php

/*
|--------------------------------------------------------------------------
| Menu de navegação
|--------------------------------------------------------------------------
|
| Agrupa os cadastros exibidos na barra lateral e no painel inicial. A chave
| de cada item é o nome base da rota resource (ex.: "titulos" → titulos.index).
|
*/

return [
    'Acervo' => [
        'titulos' => ['label' => 'Títulos', 'icon' => '▤'],
        'exemplares' => ['label' => 'Exemplares', 'icon' => '◫'],
        'historias' => ['label' => 'Histórias', 'icon' => '☰'],
    ],
    'Personagens' => [
        'personagens' => ['label' => 'Personagens', 'icon' => '♟'],
        'imagens-personagem' => ['label' => 'Imagens', 'icon' => '◩'],
        'personagem-criadores' => ['label' => 'Criadores', 'icon' => '✦'],
        'historia-personagens' => ['label' => 'Aparições em histórias', 'icon' => '⇄'],
    ],
    'Artistas' => [
        'artistas' => ['label' => 'Artistas', 'icon' => '✎'],
        'artista-historias' => ['label' => 'Participações', 'icon' => '⇆'],
    ],
    'Tabelas auxiliares' => [
        'editoras' => ['label' => 'Editoras', 'icon' => '▣'],
        'universos' => ['label' => 'Universos', 'icon' => '◎'],
        'tipos-titulo' => ['label' => 'Tipos de título', 'icon' => '◇'],
        'situacoes-titulo' => ['label' => 'Situações de título', 'icon' => '⚑'],
        'tamanhos' => ['label' => 'Tamanhos', 'icon' => '↕'],
        'armazenagens' => ['label' => 'Armazenagens', 'icon' => '▥'],
    ],
    'Administração' => [
        'usuarios' => ['label' => 'Usuários', 'icon' => '♙'],
    ],
];
