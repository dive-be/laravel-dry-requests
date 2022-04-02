<?php declare(strict_types=1);

namespace Tests\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Tests\Http\Requests\PreflightRequest;

class PreflightController
{
    public function __invoke(PreflightRequest $request): JsonResponse
    {
        return new JsonResponse([
            'message' => 'OK',
        ]);
    }
}
