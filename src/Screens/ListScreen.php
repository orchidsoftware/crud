<?php

namespace Orchid\Crud\Screens;

use Orchid\Crud\Concerns\Arbitrable;
use Orchid\Crud\Resource;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class ListScreen extends Screen
{
    use Arbitrable;

    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'CrudScreen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'CrudScreen';

    /**
     * The resource associated with the model.
     *
     * @var Resource
     */
    private $resource;

    /**
     * Query data.
     *
     * @param string     $resourceKey
     *
     * @return array
     */
    public function query(string $resourceKey): array
    {
        /** @var Resource $resource */
        $this->resource = $this->arbitrationFindOrFail($resourceKey);

        return [
            'model' => $this->resource->getModel()->filters()->paginate()
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
            Link::make($this->resource::createButtonLabel())
                ->href('#')
                ->icon('plus')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        $grid = $this->resource->grid();
        $grid[] = TD::set()
            ->align(TD::ALIGN_CENTER)
            ->render(function (Model $model) {
                return Link::make(__('Edit'))
                    ->route("platform.{$this->resource::uriKey()}.edit", [
                        $this->resource::uriKey(),
                        $model->getAttribute($model->getKeyName()),
                    ])
                    ->icon('pencil');
            });

        return [
            Layout::table('model', $grid)
        ];
    }
}
