<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerConfig();
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/dry-requests.php', 'dry-requests');

        $this->callAfterResolving(ExceptionHandler::class, $this->registerException(...));
    }

    private function registerConfig()
    {
        $config = 'dry-requests.php';

        $this->publishes([
            __DIR__ . '/../config/' . $config => $this->app->configPath($config),
        ], 'config');
    }

    private function registerException(Handler $handler)
    {
        $handler->ignore(SucceededException::class);
        $handler->renderable(fn (SucceededException $e) => $this->app[Responder::class]->respond());
    }
}
