<?php

namespace Orchid\Crud\Layouts;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ResourceTable extends Table
{
    public function __construct(
        protected $target,
        private $resource,
        private $request,
    ) {
    }

    protected function columns(): iterable
    {
        $grid = collect($this->resource->columns());

        $grid->prepend(TD::make()
            ->width(50)
            ->cantHide()
            ->canSee(count($this->resource->actions()) > 0)
            ->render(function (Model $model) {
                return CheckBox::make('_models[]')
                    ->value($model->getKey())
                    ->checked(false);
            }));

        if ($this->resource->canShowTableActions()) {
            $grid->push(TD::make(__('Actions'))
                ->alignRight()
                ->cantHide()
                ->render(function (Model $model) {
                    return $this->getTableActions($model)
                        ->set('align', 'justify-content-end align-items-center')
                        ->autoWidth()
                        ->render();
                }));
        }

        return $grid->toArray();
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
                ->canSee($this->request->can('view', $model))
                ->route('platform.resource.view', [
                    $this->resource::uriKey(),
                    $model->getAttribute($model->getKeyName()),
                ]),

            Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->canSee($this->request->can('update', $model))
                ->route('platform.resource.edit', [
                    $this->resource::uriKey(),
                    $model->getAttribute($model->getKeyName()),
                ]),
        ]);
    }
}
