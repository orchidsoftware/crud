<?php

namespace Orchid\Crud\Tests\Fixtures;

class PrivateResource extends PostResource
{
    /**
     * Get the permission key for the resource.
     *
     * @return string|null
     */
    public static function permission(): ?string
    {
        return 'private-resource';
    }
}
