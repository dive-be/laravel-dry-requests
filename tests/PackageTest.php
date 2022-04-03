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
    $response = dry(['nickname' => 'Moe']);

    $response->assertDry()->assertValid(['email', 'name']);
});

test('validation will stop as soon as an error is found during a dry request', function () {
    $response = dry(['email' => 'muhammed@', 'name' => 'M']);

    $response->assertValid('name')->assertInvalid('email');
});

test('202 (Accepted) is returned if a dry request succeeds', function () {
    $response = dry(['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    $response->assertDry();
});

test('event is dispatched if a request ran dry', function () {
    Event::fake();

    wet(['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    Event::assertNotDispatched(RequestRanDry::class);

    dry(['email' => 'muhammed@dive.be', 'name' => 'Muhammed Sari']);

    Event::assertDispatched(RequestRanDry::class);
});
