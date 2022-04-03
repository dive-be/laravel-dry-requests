<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\Http\Controllers\UsersController;

class TestingServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        TestResponse::macro('assertDry', function () {
            /** @var TestResponse $this */
            return $this->assertNoContent(Response::HTTP_ACCEPTED);
        });
    }

    public function map()
    {
        $this->post('users', [UsersController::class, 'store']);
    }
}
