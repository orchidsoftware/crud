<?php

namespace Orchid\Crud;

enum ResourceRoute
{

    case LIST;

    case CREATE;

    case VIEW;

    case EDIT;

    /**
     * Use for route('...')->name('') method.;
     *
     * @return string
     */
    public function name(): string
    {
        return match ($this) {
            ResourceRoute::LIST   => 'platform.resource.list',
            ResourceRoute::CREATE => 'platform.resource.create',
            ResourceRoute::VIEW   => 'platform.resource.view',
            ResourceRoute::EDIT   => 'platform.resource.edit',
        };
    }

    public static function is(ResourceRoute $route): bool
    {
        return request()->route()->getName() === $route->name();
    }
}
