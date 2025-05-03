<?php

namespace Orchid\Crud\Screens;

use Illuminate\Http\RedirectResponse;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\Exceptions\BehaviourChangers\InfoMessageChanger;
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
            ResourceFields::PREFIX => ($model = $request->model()),
            ...(
                method_exists($this->resource, 'customCreateQuery') ?
                    $this->resource->customCreateQuery($request, $model) :
                    []
            ),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(bool $skipCustomCommandBar = false): array
    {
        if (method_exists($this->resource, 'customCreateCommandBar') && ! $skipCustomCommandBar) {
            return $this->resource->customCreateCommandBar($this);
        }

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
    public function layout(bool $skipCustomLayout = false): array
    {
        /*
        * We check if the `customCreateLayout` method exists,
        * and allow you to recursively call this method passing the skipCustomLayout parameter.
        */
        if (method_exists($this->resource, 'customCreateLayout') && ! $skipCustomLayout) {
            return $this->resource->customCreateLayout($this);
        }

        $computedLayout = collect();
        if (method_exists($this->resource, 'preFormLayout')) {
            $computedLayout->merge(
                $this->resource->preFormLayout($this)
            );
        }

        $computedLayout->add(
            (new ResourceFields($this->resource->fields()))
                ->title(
                    method_exists($this->resource, 'formRowTitle') ?
                        $this->resource->formRowTitle($this) : null
                )
        );

        if (method_exists($this->resource, 'postFormLayout')) {
            $computedLayout->merge(
                $this->resource->postFormLayout($this)
            );
        }

        return $computedLayout->toArray();
    }

    /**
     * @param CreateRequest $request
     *
     * @return RedirectResponse
     */
    public function save(CreateRequest $request)
    {
        $model = $request->model();

        try {
            $request->resource()->save($request, $model);

            Toast::info($this->resource::createToastMessage());
        } catch (InfoMessageChanger $e) {
            Toast::info($e->getMessage());
        }

        if ($request->resource()::$redirectToViewAfterSaving) {
            return redirect()->route('platform.resource.view', [
                'resource' => $request->resource,
                'id'       => $model->getKey(),
            ]);
        } else {
            return redirect()->route('platform.resource.list', $request->resource);
        }
    }
}
