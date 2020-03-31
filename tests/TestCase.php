<?php


namespace LaravelPro\ReachSeeder\Tests;

use LaravelPro\ReachSeeder\ReachSeederServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [ReachSeederServiceProvider::class];
    }
}
