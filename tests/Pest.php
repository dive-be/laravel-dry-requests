<?php declare(strict_types=1);

use Dive\DryRequests\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use function Pest\Laravel\post;

uses(TestCase::class)->in(__DIR__);

function dry(string $endpoint, string $behavior, array $parameters = []): TestResponse
{
    return post($endpoint, $parameters, [ServiceProvider::HEADER => $behavior]);
}

function wet(string $endpoint, array $parameters = []): TestResponse
{
    return post($endpoint, $parameters);
}
