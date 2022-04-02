<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Tests\Http\Controllers\PreflightController;

class TestingServiceProvider extends RouteServiceProvider
{
    public function map()
    {
        $this->get('preflight', PreflightController::class)->name('preflight');
    }
}
