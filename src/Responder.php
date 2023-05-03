<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Http\Response;

/** @internal */
final class Responder
{
    public function respond(): Response
    {
        return new Response(headers: ['Vary' => ServiceProvider::HEADER]);
    }
}
