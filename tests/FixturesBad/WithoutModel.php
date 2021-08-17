<?php

namespace Orchid\Crud\Tests\FixturesBad;

use Orchid\Crud\Resource;

class WithoutModel extends Resource
{
    public function columns(): array
    {
        return [];
    }

    public function fields(): array
    {
        return [];
    }

    public function legend(): array
    {
        return [];
    }
}
