<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Dive\Enum\Assertable;
use Dive\Enum\ValueListable;

/**
 * @method bool isFirstFailure()
 */
enum Validation: string
{
    use Assertable;
    use ValueListable;

    case AllFailures = 'all';
    case FirstFailure = 'first';
}
