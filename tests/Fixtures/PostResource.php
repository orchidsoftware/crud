<?php

namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
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
        return [
            TD::set('title'),
            TD::set('description'),
            TD::set('body'),
            TD::set('created_at'),
            TD::set('updated_at'),
        ];
    }

    /**
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('title')
                ->title('Title')
                ->help('A string containing the name text and design to attract attention'),

            TextArea::make('description'),

            TextArea::make('body'),
        ];
    }

    /**
     * Get the validation rules that apply to save/update.
     *
     * @param ResourceRequest|null $request
     *
     * @return array
     */
    public function rules(ResourceRequest $request = null): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'body' => 'required|string',
        ];
    }
}
