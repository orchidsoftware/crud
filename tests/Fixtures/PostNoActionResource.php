<?php

namespace Orchid\Crud\Tests\Fixtures;

class PostNoActionResource extends PostResource
{
    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(): array
    {
       return [];
    }
}
