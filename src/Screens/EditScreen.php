<?php

namespace Orchid\Crud\Screens;

use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class EditScreen extends Screen
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * Query data.
     *
     * @param ResourceRequest $request
     *
     * @return array
     */
    public function query(ResourceRequest $request): array
    {
        $this->resource = $request->resource();
        $this->name = $this->resource::label();

        return [
            'model' => $request->findModelOrFail(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make($this->resource::updateButtonLabel())
                ->href('#')
                ->icon('check'),

            Link::make('test')
                ->href('#')
                ->icon('trash'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        $fields = array_map(function (Field $field) {
            return $field->set('name', 'model.' . $field->get('name'));
        }, $this->resource->fields());

        return [
            Layout::rows($fields),
        ];
    }
}
