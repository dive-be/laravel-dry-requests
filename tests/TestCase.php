<?php declare(strict_types=1);

namespace Tests;

use Dive\DryRequests\ServiceProvider;
use Orchestra\Testbench\TestCase as TestCaseBase;

class TestCase extends TestCaseBase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            TestingServiceProvider::class,
        ];
    }
}
