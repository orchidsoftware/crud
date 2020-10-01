<?php

namespace Orchid\Crud\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Orchid\Crud\CrudServiceProvider;
use Orchid\Platform\Providers\FoundationServiceProvider;

class TestCase extends Orchestra
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array|string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            FoundationServiceProvider::class,
            CrudServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
