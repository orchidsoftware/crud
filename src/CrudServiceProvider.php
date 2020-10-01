<?php

namespace Orchid\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
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
