<?php

namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;

class ExampleResource extends Resource
{
    /**
     * @return array
     */
    public function grid(): array
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
