<?php

namespace Orchid\Crud\Screens;

use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Layouts\ResourceFields;
use Orchid\Crud\Requests\UpdateRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;

class EditScreen extends CrudScreen
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Query data.
     *
     * @param UpdateRequest $request
     *
     * @return array
     */
    public function query(UpdateRequest $request): array
    {
        $this->model = $request->findModelOrFail();

        return [
            ResourceFields::PREFIX => $this->model,
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
            Button::make($this->resource::updateButtonLabel())
                ->canSee($this->request->can('update'))
                ->method('update')
                ->icon('bs.check-circle')
                ->parameters([
                    '_retrieved_at' => optional($this->model->{$this->model->getUpdatedAtColumn()})->toJson(),
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
        return [
            new ResourceFields($this->resource->fields()),
        ];
    }
}
