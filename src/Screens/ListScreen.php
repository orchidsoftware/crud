<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Requests\IndexRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ListScreen extends CrudScreen
{
    /**
     * Query data.
     *
     * @param IndexRequest $request
     *
     * @return array
     */
    public function query(IndexRequest $request): array
    {
        return [
            'models' => $request->getModelPaginationList(),
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
                ->route('platform.resource.create', $this->resource::uriKey())
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
        $grid = $this->resource->columns();

        $grid[] = TD::make(__('Actions'))
            ->align(TD::ALIGN_RIGHT)
            ->cantHide()
            ->canSee($this->can('update'))
            ->render(function (Model $model) {
                return Link::make(__('Edit'))
                    ->icon('pencil')
                    ->route('platform.resource.edit', [
                        $this->resource::uriKey(),
                        $model->getAttribute($model->getKeyName()),
                    ]);
            });

        return [
            Layout::selection($this->resource->filters()),
            Layout::table('models', $grid),
        ];
    }
}
