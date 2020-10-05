<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ListScreen extends CrudScreen
{
    /**
     * Query data.
     *
     * @param ResourceRequest $request
     *
     * @return array
     */
    public function query(ResourceRequest $request): array
    {
        return [
            'model' => $request->model()
                ->with($this->resource->with())
                ->filters()
                ->filtersApply($this->resource->filters())
                ->paginate(),
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
            Layout::selection($this->resource->filters()),
            Layout::table('model', $grid),
        ];
    }
}
