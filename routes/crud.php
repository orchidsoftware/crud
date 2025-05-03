<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Orchid\Crud\Http\Controllers\AsyncController;
use Orchid\Crud\ResourceRequest;
use Orchid\Crud\Screens\CreateScreen;
use Orchid\Crud\Screens\ViewScreen;
use Orchid\Crud\Screens\EditScreen;
use Orchid\Crud\Screens\ListScreen;
use Tabuna\Breadcrumbs\Trail;

Route::prefix(config('platform.crud_prefix', '/crud'))->group(
    function () {
        Route::screen('/create/{resource?}', CreateScreen::class)
            ->name('resource.create')
            ->breadcrumbs(function (Trail $trail) {
                $resource = app(ResourceRequest::class)->resource();

                return $trail
                    ->parent('platform.resource.list')
                    ->push($resource::createBreadcrumbsMessage());
            });

        Route::screen('/view/{resource?}/{id}', ViewScreen::class)
            ->name('resource.view')
            ->breadcrumbs(function (Trail $trail) {
                $resource = app(ResourceRequest::class)->resource();
                $id = request()->route('id');

                return $trail
                    ->parent('platform.resource.list')
                    ->push(request()->route('id'), \route('platform.resource.view', [$resource::uriKey(), $id]));
            });

        Route::screen('/edit/{resource?}/{id}', EditScreen::class)
            ->name('resource.edit')
            ->breadcrumbs(function (Trail $trail, $name, $id) {
                $resource = app(ResourceRequest::class)->resource();

                return $trail
                    ->parent('platform.resource.view', [$name, $id])
                    ->push($resource::editBreadcrumbsMessage());
            });

        Route::screen('/list/{resource?}', ListScreen::class)
            ->name('resource.list')
            ->breadcrumbs(function (Trail $trail) {
                $resource = app(ResourceRequest::class)->resource();

                return $trail->parent('platform.index')
                    ->push($resource::listBreadcrumbsMessage(), \route('platform.resource.list', [$resource::uriKey()]));
            });
    }
);

Route::post('/listener/{screen}/{layout}/{resource}/{id?}', [AsyncController::class, 'crudListener'])
    ->name('async.listener.crud');
