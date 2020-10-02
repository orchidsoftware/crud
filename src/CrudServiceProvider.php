<?php

namespace Orchid\Crud;

use App\Orchid\Resource\RoleResource;
use App\Orchid\Resource\UserResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Orchid\Crud\Commands\ResourceCommand;
use Orchid\Crud\Screens\EditScreen;
use Orchid\Crud\Screens\ListScreen;
use Orchid\Platform\Providers\FoundationServiceProvider;
use Orchid\Support\Facades\Dashboard;
use Tabuna\Breadcrumbs\BreadcrumbsServiceProvider;

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
        $this->app->register(BreadcrumbsServiceProvider::class);
        $this->app->register(FoundationServiceProvider::class);

        $this->app->singleton(Arbitrator::class, static function () {
            return new Arbitrator();
        });

        /** @var Arbitrator $arbitrator */
        $arbitrator = app(Arbitrator::class);


        $arbitrator->resources([
            UserResource::class,
            RoleResource::class,
            // ... UserResource::class
        ])->boot();


        Route::domain((string)config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(function ($route) {
                $route->screen('/resources/{resource?}/{id}', EditScreen::class)
                    ->name('resource.edit');

                $route->screen('/resources/{resource?}', ListScreen::class)
                    ->name('resource.list');
            });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
