<?php

namespace Orchid\Crud;

enum ResourceRoute: string
{

    case LIST = 'platform.resource.list';

    case CREATE = 'platform.resource.create';

    case VIEW = 'platform.resource.view';

    case EDIT = 'platform.resource.edit';

    public static  function is(ResourceRoute $route): bool
    {
        return request()->route()->getName() === $route->value;
    }

}
