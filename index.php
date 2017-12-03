<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

header('Content-Type: application/json');

$ics = [
    '8-ball' => [
        'title' => '8 Ball'
    ],
    'baseball' => [
        'title' => 'Baseball'
    ],
    'basketball' => [
        'title' => 'Basketball'
    ],
    'battleship' => [
        'title' => 'Battleship'
    ],
    'bow-arrow' => [
        'title' => 'Bow & Arrow'
    ],
    'bowling' => [
        'title' => 'Bowling'
    ],
    'boxing' => [
        'title' => 'Boxing'
    ],
    'boys' => [
        'title' => 'Boys'
    ],
    'bubbles' => [
        'title' => 'Bubbles'
    ],
    'candy' => [
        'title' => 'Candy'
    ],
    'car' => [
        'title' => 'Car'
    ],
    'cards' => [
        'title' => 'Cards'
    ],
    'cat' => [
        'title' => 'Cat'
    ],
    'checkers' => [
        'title' => 'Checkers'
    ],
    'chess' => [
        'title' => 'Chess'
    ],
    'cooking' => [
        'title' => 'Cooking'
    ],
    'cop' => [
        'title' => 'Cop'
    ],
    'crash' => [
        'title' => 'Crash'
    ],
    'cricket' => [
        'title' => 'Cricket'
    ],
    'demolition' => [
        'title' => 'Demolition'
    ],
    'detective' => [
        'title' => 'Detective'
    ],
    'dinosaur' => [
        'title' => 'Dinosaur'
    ],
    'dog' => [
        'title' => 'Dog'
    ],
    'dolphin' => [
        'title' => 'Dolphin'
    ],
    'dragon' => [
        'title' => 'Dragon'
    ],
    'driving' => [
        'title' => 'Driving'
    ],
    'escape' => [
        'title' => 'Escape'
    ],
];

function itemCount(string $slug): int {
    return max(96, crc32($slug) % 100000);
}

$images = [
    'https://media.giphy.com/media/KI4uSNrKSg1eU/giphy.gif',
];


function fail() {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo json_encode([
        'error' => 'Invalid Request',
    ]);
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/api/item-collection/') === 0) {
    $slug = substr($_SERVER['REQUEST_URI'], strlen('/api/item-collection/'));
    if ( ! isset($ics[$slug])) {
        fail();
    }

    $ic = $ics[$slug];

    echo json_encode([
        'slug' => $slug,
        'title' => $ic['title'],
    ]);
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/api/item-by-slug/') === 0) {
    $slug = substr($_SERVER['REQUEST_URI'], strlen('/api/item-by-slug/'));
    if ( ! isset($ics[$slug])) {
        fail();
    }

    $ic = $ics[$slug];
    $total = itemCount($slug);
    $itemCount = min(100, $total);

    $items = array_map(function ($id) use ($images) {
        return [
          'title' => substr(md5($id), 0, rand(8, 20)),
          'image' => $images[0],
          'since' => '1 year',
          'url' => 'https://www.google.com',
        ];
    }, range(1, $itemCount));

    echo json_encode([
        'pagination' => [
            'total' => $total,
            'page' => 1,
            'per_page' => 100,
        ],
        'items' => $items,
    ]);
    exit();
}

switch ($_SERVER['REQUEST_URI']) {
    case '/api/item-collections':
        echo json_encode(
            array_map(function (string $slug, array $data) {
                return [
                    'slug' => $slug,
                    'title' => $data['title'],
                ];
            }, array_keys($ics), $ics)
        );
        break;

    case '/api/item-collection-previews':
        echo json_encode(
            array_map(function (string $slug, array $data) use ($images) {
                return [
                    'slug' => $slug,
                    'title' => $data['title'],
                    'image' => $images[0],
                ];
            }, array_keys($ics), $ics)
        );
        break;

    default:
        fail();
}

