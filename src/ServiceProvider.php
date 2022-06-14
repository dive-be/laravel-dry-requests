<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\ServiceProvider as ServiceProviderBase;

final class ServiceProvider extends ServiceProviderBase
{
    public const HEADER = 'X-Dry-Run';

    public function register()
    {
        $this->app->afterResolving(ExceptionHandler::class, $this->registerException(...));
    }

    private function registerException(ExceptionHandler $handler)
    {
        if ($handler instanceof Handler) {
            $handler->ignore(SucceededException::class);
            $handler->renderable(fn (SucceededException $e) => $this->app[Responder::class]->respond());
        }
    }
}
