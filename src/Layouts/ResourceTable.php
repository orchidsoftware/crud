<?php

namespace Orchid\Crud\Layouts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Orchid\Crud\Action;
use Orchid\Crud\ResourceRoute;
use Orchid\Screen\Action as ActionButton;
use Orchid\Screen\Actions\DropDown;
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
                return CheckBox::make($this->actionKey())
                    ->value($model->getKey())
                    ->checked(false);
            }));

        if ($this->resource->canShowTableActions()) {
            $grid->push(
                TD::make(__('Actions'), $this->getActionsTitle())
                ->alignRight()
                ->cantHide()
                ->render(function (Model $model) {
                    return $this->getTableActions($model)
                        ->set('align', 'justify-content-end align-items-center')
                        ->autoWidth()
                        ->render();
                }),
            );
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
                ->route(ResourceRoute::VIEW->name(), [
                    $this->resource::uriKey(),
                    $model->getAttribute($model->getKeyName()),
                ]),

            Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->canSee($this->request->can('update', $model))
                ->route(ResourceRoute::VIEW->name(), [
                    $this->resource::uriKey(),
                    $model->getAttribute($model->getKeyName()),
                ]),
        ]);
    }

    private function getActionsTitle(): ?DropDown
    {
        if ($this->availableActions()->isEmpty()) {
            return null;
        }

        return DropDown::make('Actions')
            ->icon('bs.three-dots-vertical')
            ->list($this->availableActions()->toArray());
    }

    private function availableActions(): Collection
    {
        return $this->actions()
            ->map(function (Action $action) {
                return $action->button()
                    ->method($this->actionMethod())
                    ->parameters(array_merge(
                        $action->button()->get('parameters', []),
                        ['_action' => $this->actionParameter($action)],
                    ));
            })
            ->filter(function (ActionButton $action) {
                return $action->isSee();
            });
    }

    private function actions(): Collection
    {
        return collect($this->resource->actions())->map(function ($action) {
            return is_string($action) ? resolve($action) : $action;
        });
    }

    private function actionKey(): string
    {
        return ResourceRoute::is(ResourceRoute::VIEW) ? '_relation_models[]' : '_models[]';
    }

    private function actionMethod(): string
    {
        return ResourceRoute::is(ResourceRoute::VIEW) ? 'relation' : 'action';
    }

    private function actionParameter(Action $action): string
    {
        return ResourceRoute::is(ResourceRoute::VIEW)
            ? Crypt::encryptString(get_class($action))
            : $action->name();
    }
}
