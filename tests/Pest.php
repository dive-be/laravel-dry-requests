<?php declare(strict_types=1);

use Dive\DryRequests\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use function Pest\Laravel\post;

uses(TestCase::class)->in(__DIR__);

function dry(array $parameters = []): TestResponse
{
    return post('users', $parameters, [ServiceProvider::HEADER => true]);
}

function wet(array $parameters = []): TestResponse
{
    return post('users', $parameters);
}
