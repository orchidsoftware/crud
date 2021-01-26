<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Requests\IndexRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
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
            'model' => $request->getModelPaginationList(),
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
            $this->actionsButtons(),
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
        $grid = collect($this->resource->columns());

        $grid->prepend(TD::make()
            ->width(50)
            ->cantHide()
            ->canSee($this->availableActions()->isNotEmpty())
            ->render(function (Model $model) {
                return CheckBox::make('_models[]')
                    ->value($model->getKey())
                    ->checked(false);
            }));

        $grid->push(TD::make(__('Actions'))
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
            }));

        return [
            Layout::selection($this->resource->filters()),
            Layout::table('model', $grid->toArray()),
        ];
    }
}
