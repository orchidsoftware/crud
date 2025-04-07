<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Layouts\ResourceFields;
use Orchid\Crud\Layouts\ResourceRelationsMenu;
use Orchid\Crud\Layouts\ResourceTable;
use Orchid\Crud\Requests\ViewRequest;
use Orchid\Crud\Resource;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class ViewScreen extends CrudScreen
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var null|Resource
     */
    protected $relation;

    /**
     * Query data.
     *
     * @param ViewRequest $request
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function query(ViewRequest $request): array
    {
        $this->model = $request->findModelOrFail();

        $this->relation = $request->resource()->relation()->findRelationResource();
        $relationPaginate = $request->resource()->relation()->getRelationPaginationList($this->model);

        return [
            ResourceFields::PREFIX => $this->model,
            '_relation' => $relationPaginate,
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

            Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->canSee($this->can('update'))
                ->route('platform.resource.edit', [
                    $this->resource::uriKey(),
                    $this->model->getKey(),
                ]),

            Button::make($this->resource::deleteButtonLabel())
                ->novalidate()
                ->confirm(__('Are you sure you want to delete this resource?'))
                ->canSee(! $this->isSoftDeleted() && $this->can('delete'))
                ->method('delete')
                ->icon('bs.trash'),

            Button::make($this->resource::deleteButtonLabel())
                ->novalidate()
                ->confirm(__('Are you sure you want to force delete this resource?'))
                ->canSee($this->isSoftDeleted() && $this->can('forceDelete'))
                ->method('forceDelete')
                ->icon('bs.trash'),

            Button::make($this->resource::restoreButtonLabel())
                ->novalidate()
                ->confirm(__('Are you sure you want to restore this resource?'))
                ->canSee($this->isSoftDeleted() && $this->can('restore'))
                ->method('restore')
                ->icon('bs.arrow-clockwise'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        $layout = [
            Layout::legend(ResourceFields::PREFIX, $this->resource->legend()),
        ];

        if ($this->relation) {
            // Show the relation resource
            $layout[] = new ResourceRelationsMenu($this->resource);


            // Show the relation resource table
            $layout[] = Layout::selection($this->relation->filters());
            $layout[] = new ResourceTable('_relation', $this->relation, $this->request);
        }

        return $layout;
    }
}
