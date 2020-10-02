<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ListScreen extends Screen
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
            'model' => $request->model()->filters()->paginate(),
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
            Link::make($this->resource::createButtonLabel())
                ->href('#')
                ->icon('plus'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        $grid = $this->resource->grid();
        $grid[] = TD::set()
            ->align(TD::ALIGN_RIGHT)
            ->cantHide()
            ->render(function (Model $model) {
                return Link::make(__('Edit'))
                    ->route('platform.resource.edit', [
                        $this->resource::uriKey(),
                        $model->getAttribute($model->getKeyName()),
                    ])
                    ->icon('pencil');
            });

        return [
            Layout::table('model', $grid),
        ];
    }
}
