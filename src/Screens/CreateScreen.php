<?php

namespace Orchid\Crud\Screens;

use Illuminate\Http\RedirectResponse;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Layouts\ResourceFields;
use Orchid\Crud\Requests\CreateRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class CreateScreen extends CrudScreen
{
    /**
     * Query data.
     *
     * @param CreateRequest $request
     *
     * @return array
     */
    public function query(CreateRequest $request): array
    {
        return [
            ResourceFields::PREFIX => $request->model(),
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
            Button::make($this->resource::createButtonLabel())
                ->method('save')
                ->icon('bs.check-circle'),
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

    /**
     * @param CreateRequest $request
     *
     * @return RedirectResponse
     */
    public function save(CreateRequest $request)
    {
        $model = $request->model();

        $request->resource()->save($request, $model);

        Toast::info($this->resource::createToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }
}
