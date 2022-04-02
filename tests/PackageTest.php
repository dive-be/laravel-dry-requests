<?php declare(strict_types=1);

namespace Tests;

use function Pest\Laravel\get;

test('route', function () {
    $response = get('preflight');

    $response->assertJson(['message' => 'OK']);
});
