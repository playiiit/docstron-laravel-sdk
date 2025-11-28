<?php

namespace Docstron\Laravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Docstron\Laravel\DocstronServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            DocstronServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Docstron' => \Docstron\Laravel\Facades\Docstron::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('docstron.api_key', 'test_api_key');
        $app['config']->set('docstron.base_url', 'https://api.docstron.com/v1');
    }
}
