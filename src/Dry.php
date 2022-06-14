<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Dry
{
    public function __construct(
        public readonly Validation $behavior,
    ) {}
}
