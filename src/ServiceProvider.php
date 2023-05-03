<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider as ServiceProviderBase;

final class ServiceProvider extends ServiceProviderBase
{
    public const HEADER = 'X-Dry-Run';

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerConfig();
        }

        Request::mixin(new DryRequestMacros());
    }

    public function register(): void
    {
        $this->callAfterResolving(ExceptionHandler::class, $this->registerException(...));

        $this->mergeConfigFrom(__DIR__ . '/../config/dry-requests.php', 'dry-requests');
    }

    private function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/dry-requests.php' => $this->app->configPath('dry-requests.php'),
        ], 'config');
    }

    private function registerException(ExceptionHandler $handler): void
    {
        if ($handler instanceof Handler) {
            $handler->ignore(SucceededException::class);
            $handler->renderable(fn (SucceededException $e) => $this->app->make(Responder::class)->respond());
        }
    }
}
