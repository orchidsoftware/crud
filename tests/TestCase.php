<?php

namespace Orchid\Crud\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Orchid\Crud\Arbitrator;
use Orchid\Crud\CrudServiceProvider;
use Orchid\Crud\Middleware\BootCrudGenerator;
use Orchid\Crud\ResourceFinder;
use Orchid\Platform\Providers\FoundationServiceProvider;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Dashboard;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Watson\Active\Facades\Active;

class TestCase extends Orchestra
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(realpath('./tests/Migrations'));
        $this->artisan('orchid:publish');

        Factory::guessFactoryNamesUsing(function ($factory) {
            $factoryBasename = class_basename($factory);

            return "Orchid\Crud\Tests\Factories\\$factoryBasename".'Factory';
        });

        $resources = $this
            ->getResourceFinder()
            ->setNamespace('Orchid\Crud\Tests\Fixtures')
            ->find(__DIR__ . '/Fixtures');

        app(Arbitrator::class)->resources($resources);
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
        $app['config']->set('platform.auth', false);
        $app['config']->set('platform.middleware.public', ['web']);
        $app['config']->set('platform.middleware.private', ['web', BootCrudGenerator::class]);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @return ResourceFinder
     */
    protected function getResourceFinder(): ResourceFinder
    {
        return app(ResourceFinder::class);
    }

    /**
     * Load the migrations for the test environment.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--realpath' => realpath(__DIR__.'/Migrations'),
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Alert'       => Alert::class,
            'Active'      => Active::class,
            'Breadcrumbs' => Breadcrumbs::class,
            'Dashboard'   => Dashboard::class,
        ];
    }

    /**
     * Set the URL of the previous request.
     *
     * @param string $url
     *
     * @return $this
     */
    public function from(string $url)
    {
        session()->setPreviousUrl($url);

        return $this;
    }
}
