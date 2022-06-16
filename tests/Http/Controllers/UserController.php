<?php declare(strict_types=1);

namespace Tests\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Tests\Http\Requests\StoreUserRequest;

class UserController
{
    public function store(StoreUserRequest $request): JsonResponse
    {
        return new JsonResponse($request->validated());
    }
}
