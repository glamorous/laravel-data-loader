<?php

namespace Glamorous\DataLoader\Tests;

use Glamorous\DataLoader\ServiceProviders\DataLoaderServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DataLoaderServiceProvider::class,
        ];
    }
}
