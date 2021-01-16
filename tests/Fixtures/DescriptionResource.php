<?php

namespace Orchid\Crud\Tests\Fixtures;

class DescriptionResource extends PostResource
{
    /**
     * @return string|null
     */
    public static function description(): ?string
    {
        return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    }
}
