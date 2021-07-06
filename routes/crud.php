<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Orchid\Crud\ResourceRequest;
use Orchid\Crud\Screens\CreateScreen;
use Orchid\Crud\Screens\EditScreen;
use Orchid\Crud\Screens\ListScreen;
use Orchid\Crud\Screens\ViewScreen;
use Tabuna\Breadcrumbs\Trail;

// ::screen(...) macro is in Platform's FoundationServiceProvider.php
{
    if (! Route::hasMacro('screenMatch')) {
        Route::macro('screenMatch', function ($httpMethods, $url, $screen) {
            /* @var Router $this */
            $route = $this->match($httpMethods, $url.'/{method?}', [$screen, 'handle']);

            $methods = $screen::getAvailableMethods();

            if (! empty($methods)) {
                $route->where('method', implode('|', $methods));
            }

            return $route;
        });
    }
}

/*
 * https://github.com/orchidsoftware/crud/issues/47
 *
 * # CURRENT
 *
 * list     GET|HEAD    /admin/crud/documents
 * create   GET|HEAD    /admin/crud/documents/create
 *          POST        /admin/crud/documents/create/save
 * view     GET|HEAD    /admin/crud/documents/{id}
 * edit     GET|HEAD    /admin/crud/documents/{id}/edit
 *          POST        /admin/crud/documents/{id}/edit/update
 *          POST        /admin/crud/documents/{id}/edit/delete
 *
 * # DESIRED
 *
 * index    GET|HEAD	/admin/crud/documents
 * create   GET|HEAD	/admin/crud/documents/create
 * store    POST	    /admin/crud/documents
 * show     GET|HEAD	/admin/crud/documents/{id}
 * edit     GET|HEAD	/admin/crud/documents/{id}/edit
 * update   PUT|PATCH   /admin/crud/documents/{id}
 * destroy  DELETE      /admin/crud/documents/{id}
 */

Route::screenMatch(['GET', 'HEAD'], '/crud/{resource?}', ListScreen::class)
    ->name('resource.list')
    ->breadcrumbs(function (Trail $trail) {
        $resource = app(ResourceRequest::class)->resource();

        return $trail->parent('platform.index')
            ->push($resource::listBreadcrumbsMessage(), \route('platform.resource.list', [$resource::uriKey()]));
    });

Route::screenMatch(['GET', 'HEAD', 'POST'], '/crud/{resource?}/create', CreateScreen::class)
    ->name('resource.create')   // and resource.store
    ->breadcrumbs(function (Trail $trail) {
        $resource = app(ResourceRequest::class)->resource();

        return $trail
            ->parent('platform.resource.list')
            ->push($resource::createBreadcrumbsMessage());
    });

Route::screenMatch(['GET', 'HEAD'], '/crud/{resource?}/{id}', ViewScreen::class)
    ->name('resource.view')
    ->breadcrumbs(function (Trail $trail) {
        $resource = app(ResourceRequest::class)->resource();
        $id = request()->route('id');

        return $trail
            ->parent('platform.resource.list')
            ->push(request()->route('id'), \route('platform.resource.view', [$resource::uriKey(), $id]));
    });

Route::screenMatch(['GET', 'HEAD', 'POST'], '/crud/{resource?}/{id}/edit', EditScreen::class)
    ->name('resource.edit') // resource.update and resource.destroy
    ->breadcrumbs(function (Trail $trail, $name, $id) {
        $resource = app(ResourceRequest::class)->resource();

        return $trail
            ->parent('platform.resource.view', [$name, $id])
            ->push($resource::editBreadcrumbsMessage());
    });
