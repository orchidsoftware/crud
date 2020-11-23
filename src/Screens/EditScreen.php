<?php

namespace Orchid\Crud\Screens;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditScreen extends CrudScreen
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Query data.
     *
     * @param ResourceRequest $request
     *
     * @return array
     */
    public function query(ResourceRequest $request): array
    {
        $this->model = $request->findModelOrFail();

        return [
            'model' => $this->model,
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
                ->method('update')
                ->icon('check'),

            Button::make($this->resource::deleteButtonLabel())
                ->confirm(__('Are you sure you want to delete this resource?'))
                ->canSee(! $this->isSoftDeleted())
                ->method('delete')
                ->icon('trash'),

            Button::make($this->resource::deleteButtonLabel())
                ->confirm(__('Are you sure you want to force delete this resource?'))
                ->canSee($this->isSoftDeleted())
                ->method('forceDelete')
                ->icon('trash'),


            Button::make($this->resource::restoreButtonLabel())
                ->confirm(__('Are you sure you want to restore this resource?'))
                ->canSee($this->isSoftDeleted())
                ->method('restore')
                ->icon('reload'),
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

    /**
     * @param ResourceRequest $request
     *
     * @return RedirectResponse
     */
    public function update(ResourceRequest $request)
    {
        $request->resource()->onSave($request, $request->findModelOrFail());

        Toast::info($this->resource::updateToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param ResourceRequest $request
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(ResourceRequest $request)
    {
        $request->resource()->onDelete(
            $request->findModelOrFail()
        );

        Toast::info($this->resource::deleteToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param ResourceRequest $request
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function forceDelete(ResourceRequest $request)
    {
        $request->resource()->onForceDelete(
            $request->findModelOrFail()
        );

        Toast::info($this->resource::deleteToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param ResourceRequest $request
     *
     * @return RedirectResponse
     */
    public function restore(ResourceRequest $request)
    {
        $request->resource()->onRestore(
            $request->findModelOrFail()
        );

        Toast::info($this->resource::restoreToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * Determine if the resource is soft deleted.
     *
     * @return bool
     */
    private function isSoftDeleted(): bool
    {
        return $this->resource::softDeletes() && $this->model->trashed();
    }
}
