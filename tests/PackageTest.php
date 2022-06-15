<?php declare(strict_types=1);

namespace Tests;

use Dive\DryRequests\RequestRanDry;
use Illuminate\Support\Facades\Event;

test('standard request behavior is unchanged', function () {
    $responseA = wet();
    $responseA->assertInvalid(['email', 'name']);

    $responseB = wet($data = ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);
    $responseB->assertJson($data)->assertValid(['email', 'name']);
});

test('fields will not be validated if absent', function () {
    $response = dry('first', ['nickname' => 'Moe']);

    $response->assertDry()->assertValid(['email', 'name']);
});

test('validation will stop as soon as an error is found during a "first failure" dry request', function () {
    $response = dry('first', ['email' => 'muhammed@', 'name' => 'M']);

    $response->assertValid('name')->assertInvalid('email');
});

test('invalid behavior will invoke the default one in the config', function () {
    $response = dry('PogChamp', ['email' => 'muhammed@', 'name' => 'M']);

    $response->assertValid('name')->assertInvalid('email');
});

test('validation will return all errors during an "all failures" dry request', function () {
    $response = dry('all', ['email' => 'muhammed@', 'name' => 'M']);

    $response->assertInvalid(['email', 'name']);
});

test('200 (OK) is returned if a dry request succeeds', function () {
    $response = dry('first', ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    $response->assertDry();
});

test('event is dispatched if a request ran dry', function () {
    Event::fake();

    wet(['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    Event::assertNotDispatched(RequestRanDry::class);

    dry('all', ['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    Event::assertDispatched(RequestRanDry::class);
});
