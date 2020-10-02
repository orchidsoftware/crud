<?php

namespace Orchid\Crud;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Orchid\Crud\Screens\EditScreen;
use Orchid\Crud\Screens\ListScreen;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Menu;
use Orchid\Support\Facades\Dashboard;
use Tabuna\Breadcrumbs\Breadcrumbs;

class Arbitrator
{
    /**
     * The registered resource names.
     *
     * @var Collection
     */
    public $resources;

    /**
     * Arbitrator constructor.
     */
    public function __construct()
    {
        $this->resources = collect();
    }

    /**
     * Register the given resources.
     *
     * @param string[] $resources
     *
     * @return Arbitrator
     */
    public function resources(array $resources): Arbitrator
    {
        $this->resources = $this->resources
            ->merge($resources)
            ->map(function ($resource) {
                return is_string($resource) ? app($resource) : $resource;
            });

        return $this;
    }

    /**
     * Registers all the resources
     */
    public function boot(): void
    {
        $this->resources->each(function (Resource $resource) {
            $this->register($resource);
        });
    }

    /**
     * @param string $key
     *
     * @return Resource|null
     */
    public function find(string $key): ?Resource
    {
        return $this->resources->filter(function (Resource $resource) use ($key) {
            return $resource::uriKey() === $key;
        })->first();
    }

    /**
     * @param string $key
     *
     * @return Resource
     */
    public function findOrFail(string $key): Resource
    {
        $resource = $this->find($key);

        abort_if($resource === null, 404);

        return $resource;
    }

    /**
     * @param Resource $resource
     *
     * @return Arbitrator
     */
    private function register(Resource $resource): Arbitrator
    {
        return $this
            //->registerRoute($resource)
            //->registerBreadcrumb($resource)
            ->registerMenu($resource)
            ->registerPermission($resource);
    }

    /**
     * @param Resource $resource
     *
     * @return Arbitrator
     */
    private function registerMenu(Resource $resource): Arbitrator
    {
        View::composer('platform::dashboard', function () use ($resource) {
            Dashboard::menu()->add(Menu::MAIN,
                ItemMenu::label($resource::label())
                    ->icon($resource::icon())
                    ->route('platform.resource.list', [$resource::uriKey()])
                    ->sort($resource::sort())
            );
        });

        return $this;
    }

    /**
     * @param Resource $resource
     *
     * @return Arbitrator
     */
    private function registerPermission(Resource $resource): Arbitrator
    {
        Dashboard::registerPermissions(
            ItemPermission::group('CRUD')
                ->addPermission($resource::uriKey(), $resource::label())
        );

        return $this;
    }

    /**
     * @param Resource $resource
     *
     * @return Arbitrator
     */
    private function registerBreadcrumb(Resource $resource): Arbitrator
    {
        Breadcrumbs::for("platform.{$resource::uriKey()}.list", static function ($trail) {
            $trail->push('List');
        });

        Breadcrumbs::for("platform.{$resource::uriKey()}.edit", static function ($trail) use ($resource) {
            $trail->parent("platform.{$resource::uriKey()}.list")
                ->push('Edit');
        });

        return $this;
    }
}
