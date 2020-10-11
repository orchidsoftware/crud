<?php

namespace Orchid\Crud\Screens;

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
     * Query data.
     *
     * @param ResourceRequest $request
     *
     * @return array
     */
    public function query(ResourceRequest $request): array
    {
        return [
            'model' => $request->findModelOrFail(),
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
                ->method('delete')
                ->icon('trash'),
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ResourceRequest $request)
    {
        $model = $request->findModelOrFail();

        $model->forceFill($request->input('model'))->save()
            ? Toast::info(__('You have successfully updated the :resource.', ['resource' => $this->resource::singularLabel()] ))
            : Toast::warning(__('An error has occurred'));

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param ResourceRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(ResourceRequest $request)
    {
        $model = $request->findModelOrFail();

        $model->delete()
            ? Toast::info(__('You have successfully deleted the :resource.', ['resource' => $this->resource::singularLabel()]))
            : Toast::warning(__('An error has occurred'));

        return redirect()->route('platform.resource.list', $request->resource);
    }
}
