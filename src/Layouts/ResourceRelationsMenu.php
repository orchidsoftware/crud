<?php

namespace Orchid\Crud\Layouts;

use Illuminate\Support\Collection;
use Orchid\Crud\Resource;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class ResourceRelationsMenu extends TabMenu {

    private Collection $relations;

    public function __construct(
        private Resource $resource,
    ) {
        $this->relations = $this->resource->relation()->available();
    }

    /**
     * Check if there are multiple relations to be displayed.
     *
     * @return bool
     */
     public function isSee(): bool
     {
         return $this->relations->count() > 1;
     }

    /**
     * Generate the navigational menu for the relations.
     *
     * @return iterable
     */
    protected function navigations(): iterable
    {
        return $this->relations
            ->map(fn(Resource $resource, string $relation) => $this->createMenuItem($resource, $relation));
    }

    /**
     * Create a Menu item for a given relation.
     *
     * @param Resource $resource
     * @param string $relation
     * @return Menu
     */
    private function createMenuItem(Resource $resource, string $relation): Menu
    {
        return Menu::make($resource->label())
            ->route('platform.resource.view', [
                'resource' => $this->resource::uriKey(),
                'id'       => $this->query->get('model')->getKey(),
                'relation' => $relation,
            ])
            ->when($this->isActive($relation), function (Menu $menu) {
                $menu->active('*');
            });
    }

    /**
     * Check if a given relation is active.
     *
     * @param string $relation
     * @return bool
     */
    private function isActive(string $relation): bool
    {
        return $this->resource->relation()->findRelationKey() === $relation;
    }
}
