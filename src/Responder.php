<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class Responder
{
    public function __construct(
        private ResponseFactory $factory,
    ) {}

    public function respond(): Response
    {
        return $this->factory->noContent(Response::HTTP_ACCEPTED);
    }
}
