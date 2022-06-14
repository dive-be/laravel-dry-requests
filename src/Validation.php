<?php declare(strict_types=1);

namespace Dive\DryRequests;

enum Validation
{
    case ContinueOnError;
    case StopOnFirstError;

    public function isStopOnFirstError(): bool
    {
        return $this === Validation::StopOnFirstError;
    }
}
