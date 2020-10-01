<?php

namespace Orchid\Crud;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Providers\FoundationServiceProvider;
use Tabuna\Breadcrumbs\BreadcrumbsServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
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
            // ... UserResource::class
        ])->boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }
}
