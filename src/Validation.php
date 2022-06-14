<?php declare(strict_types=1);

namespace Dive\DryRequests;

enum Validation
{
    case ContinueOnError;
    case StopOnFirstFailure;

    public function isStopOnFirstFailure(): bool
    {
        return $this === Validation::StopOnFirstFailure;
    }
}
