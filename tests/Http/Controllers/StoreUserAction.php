<?php declare(strict_types=1);

namespace Tests\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreUserAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|nullable',
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'nickname' => ['nullable', 'string', 'min:2', 'max:255'],
        ]);

        return new JsonResponse($validated);
    }
}
