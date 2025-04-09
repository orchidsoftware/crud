<?php

namespace Orchid\Crud;

enum ResourceRoute: string
{

    case LIST = 'platform.resource.list';

    case CREATE = 'platform.resource.create';

    case VIEW = 'platform.resource.view';

    case EDIT = 'platform.resource.edit';

}
