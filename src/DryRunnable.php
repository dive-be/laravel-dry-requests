<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DryRunnable
{
    public function __construct(
        public readonly string $parameter = 'dry-run',
    ) {}
}
