<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Exception;

final class SucceededException extends Exception
{
    public static function make(): self
    {
        return new self('You should not be seeing this. Please submit a bug report.');
    }
}
