<?php

namespace Orchid\Crud\Layouts;

use Illuminate\Support\Collection;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class ResourceRelationsMenu extends TabMenu {

    public function __construct(
        private Collection $relations,
    ) {
        $this->relations = collect($this->relations);
    }

    public static function make(array $relations): static
    {
        return new static(collect($relations));
    }

    protected function navigations(): iterable {
        if ($this->relations->count() < 2) {
            return [];
        }

        $currentRelation = request('relation');
        $firstKey = $this->relations->keys()->first();

        return $this->relations->map(function ($resource, $key) use ($currentRelation, $firstKey) {
            $menu = Menu::make($resource->label())
                ->route('platform.resource.view', [
                    'resource' => request('resource'),
                    'id' => request('id'),
                    'relation' => $key,
                ]);

            if ($currentRelation === null ? $key === $firstKey : $key === $currentRelation) {
                $menu->addClass('active');
            }

            return $menu;
        })->toArray();
    }



}
