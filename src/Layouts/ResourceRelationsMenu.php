<?php

namespace Orchid\Crud\Layouts;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class ResourceRelationsMenu extends TabMenu {

    public function __construct(
        private array $relations
    ) {}

    public static function make(array $relations): static
    {
        return new static($relations);
    }

    protected function navigations(): iterable {

        $menu = [];

        foreach ($this->relations as $resource) {
            $menu[] = Menu::make($resource->label());
        }

        return $menu;
    }

}
