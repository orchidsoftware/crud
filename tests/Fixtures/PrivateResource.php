<?php

namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Screen\Field;
use Orchid\Screen\TD;

class PrivateResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Post::class;

    /**
     * Get the permission key for the resource.
     *
     * @return string|null
     */
    public static function permission(): ?string
    {
        return 'private-resource';
    }

    /**
     * @return TD[]
     */
    public function columns(): array
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
