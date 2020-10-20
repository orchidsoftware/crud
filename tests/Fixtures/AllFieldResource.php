<?php

namespace Orchid\Crud\Tests\Fixtures;

use Orchid\Crud\Resource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\TD;


class AllFieldResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Post::class;


    public $slug = 'inputname';
    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
        ];
    }

    /**
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('inputname')
                ->type('text')
                ->required()
                ->title('Test name')
                ->help('Test help'),

            TextArea::make('textareaname')
                ->col(5)
                ->required()
                ->title('Test name')
                ->help('Test help'),

            CheckBox::make('checkboxname')
                ->value(1)
                ->title('Test name')
                ->help('Test help'),

            Select::make('selectname')
                ->options([
                    'index'   => 'Index',
                    'noindex' => 'No index',
                ])
                ->title('Test name')
                ->help('Test help'),

            DateTimer::make('datetimername')
                ->title('Test name'),

            Picture::make('picturename'),

            Input::make('manyname.input')
                ->type('text')
                ->title('Test name'),

            TextArea::make('manyname.textarea')
                ->required()
                ->title('Test name'),
        ];
    }

    /**
     * Get the validation rules that apply to save/update.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
        ];
    }
}
