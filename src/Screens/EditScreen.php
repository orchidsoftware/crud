<?php

namespace Orchid\Crud\Screens;

use Orchid\Crud\Resource;
use Orchid\Screen\Action;
use Orchid\Screen\Field;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Crud\Concerns\Arbitrable;

class EditScreen extends Screen
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
     * @var Resource
     */
    private $resource;

    /**
     * Query data.
     *
     * @param string     $resourceKey
     * @param string     $primary
     *
     * @return array
     */
    public function query(string $resourceKey, string $primary): array
    {
        /** @var Resource $resource */
        $this->resource = $this->arbitrationFindOrFail($resourceKey);

        return [
            'model' => $this->resource->getModel()->findOrFail($primary)
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
            Layout::rows($fields)
        ];
    }
}
