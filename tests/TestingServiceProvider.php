<?php declare(strict_types=1);

namespace Tests;

use Dive\DryRequests\ServiceProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Testing\TestResponse;
use Tests\Http\Controllers\UsersController;

class TestingServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        TestResponse::macro('assertDry', function () {
            /** @var TestResponse $this */
            return $this->assertNoContent(200)->assertHeader('Vary', ServiceProvider::HEADER);
        });
    }

    public function map()
    {
        $this->post('users', [UsersController::class, 'store']);
    }
}
