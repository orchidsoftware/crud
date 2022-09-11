<?php

namespace Orchid\Crud;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Orchid\Crud\Commands\ActionCommand;
use Orchid\Crud\Commands\ResourceCommand;
use Orchid\Crud\Middleware\BootCrudGenerator;
use Orchid\Platform\Providers\FoundationServiceProvider;
use Orchid\Support\Facades\Dashboard;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Path to crud dir
     *
     * @var string
     */
    protected $path;

    /**
     * The available command shortname.
     *
     * @var array
     */
    protected $commands = [
        ResourceCommand::class,
        ActionCommand::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::pushMiddlewareToGroup('platform', BootCrudGenerator::class);

        Route::domain((string)config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(__DIR__ . '/../routes/crud.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->path = dirname(__DIR__, 1);

        $this->commands($this->commands);
        $this->loadJsonTranslationsFrom($this->path.'/resources/lang/');
        $this->app->register(FoundationServiceProvider::class, true);

        $this->app->singleton(Arbitrator::class, static function () {
            return new Arbitrator();
        });
    }
}
