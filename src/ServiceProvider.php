<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\ServiceProvider as ServiceProviderBase;

final class ServiceProvider extends ServiceProviderBase
{
    public const HEADER = 'X-Dry-Run';

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerConfig();
        }
    }

    public function register()
    {
        $this->app->afterResolving(ExceptionHandler::class, $this->registerException(...));

        $this->mergeConfigFrom(__DIR__ . '/../config/dry-requests.php', 'dry-requests');
    }

    private function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/dry-requests.php' => $this->app->configPath('dry-requests.php'),
        ], 'config');
    }

    private function registerException(ExceptionHandler $handler)
    {
        if ($handler instanceof Handler) {
            $handler->ignore(SucceededException::class);
            $handler->renderable(fn (SucceededException $e) => $this->app->make(Responder::class)->respond());
        }
    }
}
