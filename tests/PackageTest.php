<?php declare(strict_types=1);

namespace Tests;

use Dive\DryRequests\RequestRanDry;
use Illuminate\Support\Facades\Event;

dataset('endpoints', [
    'users-with-form-request',
    'users-with-macro',
]);

test('standard request behavior is unchanged', function (string $endpoint) {
    $responseA = wet($endpoint);
    $responseA->assertInvalid(['email', 'name']);

    $responseB = wet($endpoint, $data = ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);
    $responseB->assertJson($data)->assertValid(['email', 'name']);
})->with('endpoints');

test('fields will not be validated if absent', function (string $endpoint) {
    $response = dry($endpoint, 'first', ['nickname' => 'Moe']);

    $response->assertDry()->assertValid(['email', 'name']);
})->with('endpoints');

test('validation will stop as soon as an error is found during a "first failure" dry request', function (string $endpoint) {
    $response = dry($endpoint, 'first', ['email' => 'muhammed@', 'name' => 'M']);

    $response->assertValid('name')->assertInvalid('email');
})->with('endpoints');

test('invalid behavior will invoke the default one in the config', function (string $endpoint) {
    $response = dry($endpoint, 'PogChamp', ['email' => 'muhammed@', 'name' => 'M']);

    $response->assertValid('name')->assertInvalid('email');
})->with('endpoints');

test('validation will return all errors during an "all failures" dry request', function (string $endpoint) {
    $response = dry($endpoint, 'all', ['email' => 'muhammed@', 'name' => 'M']);

    $response->assertInvalid(['email', 'name']);
})->with('endpoints');

test('200 (OK) is returned if a dry request succeeds', function (string $endpoint) {
    $response = dry($endpoint, 'first', ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    $response->assertDry();
})->with('endpoints');

test('event is dispatched if a request ran dry', function (string $endpoint) {
    Event::fake();

    wet($endpoint, ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    Event::assertNotDispatched(RequestRanDry::class);

    dry($endpoint, 'all', ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    Event::assertDispatched(RequestRanDry::class);
})->with('endpoints');
