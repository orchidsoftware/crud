<?php

namespace Orchid\Crud\Tests\Fixtures;

class PostActionResource extends PostResource
{
    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(): array
    {
        return [
           CustomAction::class,
       ];
    }
}
