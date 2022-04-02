<?php declare(strict_types=1);

namespace Tests;

use Dive\DryRequests\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            TestingServiceProvider::class,
        ];
    }
}
