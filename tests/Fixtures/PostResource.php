<?php


namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;
use Orchid\Screen\Field;
use Orchid\Screen\TD;

class PostResource extends Resource
{
    /**
     * @return TD[]
     */
    public function grid(): array
    {
        return [];
    }

    /**
     * @return Field[]
     */
    public function fields(): array
    {
        return [];
    }
}
