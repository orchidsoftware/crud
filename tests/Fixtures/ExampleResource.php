<?php

namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;

class ExampleResource extends Resource
{
    /**
     * @return array
     */
    public function columns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [];
    }
}
