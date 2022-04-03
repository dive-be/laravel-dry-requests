<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/dry-requests.php', 'dry-requests');

        $this->callAfterResolving(ExceptionHandler::class, $this->registerWith(...));
    }

    private function registerWith(Handler $handler)
    {
        $handler->ignore(SucceededException::class);
        $handler->renderable(fn (SucceededException $e) => $this->app[Responder::class]->respond());
    }
}
