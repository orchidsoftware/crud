<?php

namespace Orchid\Crud\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Crud\Filters\DefaultSorted;
use Orchid\Crud\Resource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
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
            TD::make('title'),
            TD::make('description'),
            TD::make('body'),
            TD::make('created_at'),
            TD::make('updated_at'),
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

            Group::make([
                TextArea::make('description'),
                TextArea::make('body'),
            ]),
        ];
    }

    /**
     * @return DefaultSorted[]
     */
    public function filters(): array
    {
        return [
           new DefaultSorted(),
       ];
    }

    /**
     * Indicates whether should check for modifications between viewing and updating a resource.
     *
     * @return bool
     */
    public static function trafficCop(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to save/update.
     *
     * @param Model $model
     *
     * @return array
     */
    public function rules(Model $model): array
    {
        return [
            'title' => [
                'string',
                'required',
                Rule::unique(self::$model, 'title')->ignore($model),
            ],
            'description' => 'required|string',
            'body'        => 'required|string',
        ];
    }
}
