<?php

namespace Orchid\Crud\Tests\FixturesBad;

use Orchid\Crud\Resource;
use Orchid\Crud\Tests\Models\PostWithoutTraits;

class WithoutTraits extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = PostWithoutTraits::class;


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
