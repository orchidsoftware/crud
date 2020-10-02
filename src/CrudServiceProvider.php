<?php

namespace Orchid\Crud;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Orchid\Crud\Commands\ResourceCommand;
use Orchid\Crud\Middleware\BootCrudGenerator;
use Orchid\Crud\Screens\EditScreen;
use Orchid\Crud\Screens\ListScreen;
use Orchid\Platform\Providers\FoundationServiceProvider;
use Orchid\Support\Facades\Dashboard;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * The available command shortname.
     *
     * @var array
     */
    protected $commands = [
        ResourceCommand::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Arbitrator::class, static function () {
            return new Arbitrator();
        });

        Route::domain((string)config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(function ($route) {
                $route->screen('/crud/{resource?}/{id}', EditScreen::class)
                    ->name('resource.edit');

                $route->screen('/crud/{resource?}', ListScreen::class)
                    ->name('resource.list');
            });

        Route::pushMiddlewareToGroup('platform', BootCrudGenerator::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->app->register(FoundationServiceProvider::class, true);
    }
}
