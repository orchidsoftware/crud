<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Requests\IndexRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
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
                ->canSee($this->can('create'))
                ->icon('bs.plus-circle'),
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
            ->alignRight()
            ->cantHide()
            ->render(function (Model $model) {
                return $this->getTableActions($model)
                    ->set('align', 'justify-content-end align-items-center')
                    ->autoWidth()
                    ->render();
            }));

        return [
            Layout::selection($this->resource->filters()),
            Layout::table('model', $grid->toArray()),
        ];
    }

    /**
     * @param Model $model
     *
     * @return Group
     */
    private function getTableActions(Model $model): Group
    {
        return Group::make([
            Link::make(__('View'))
                ->icon('bs.eye')
                ->canSee($this->can('view', $model))
                ->route('platform.resource.view', [
                    $this->resource::uriKey(),
                    $model->getAttribute($model->getKeyName()),
                ]),

            Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->canSee($this->can('update', $model))
                ->route('platform.resource.edit', [
                    $this->resource::uriKey(),
                    $model->getAttribute($model->getKeyName()),
                ]),
        ]);
    }
}
