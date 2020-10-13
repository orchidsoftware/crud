<?php


namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Screen\Field;
use Orchid\Screen\TD;

class PostResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Post::class;

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
