<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Http\Request;

final class RequestRanDry
{
    private function __construct(public readonly Request $request) {}

    public static function make(Request $request): self
    {
        return new self($request);
    }
}
