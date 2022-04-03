<?php declare(strict_types=1);

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use function Pest\Laravel\post;

uses(TestCase::class)->in(__DIR__);

function dry(array $parameters = []): TestResponse
{
    return post('users', array_merge($parameters, [config('dry-requests.parameter') => true]));
}

function wet(array $parameters = []): TestResponse
{
    return post('users', $parameters);
}
