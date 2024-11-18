<?php

namespace SchenkeIo\LaravelUrlCleaner\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], function (Repository $config) {
            //            $config->set('database.default', 'testbench');

        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            'SchenkeIo\\LaravelUrlCleaner\\LaravelUrlCleanerServiceProvider',
        ];
    }
}
