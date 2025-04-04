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

        return $this->relations->count() < 2
            ? []
            : $this->relations->map(
                fn($resource) => Menu::make($resource->label())
            );
    }

}
