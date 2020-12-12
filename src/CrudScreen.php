<?php


namespace Orchid\Crud;

use Orchid\Screen\Field;
use Orchid\Screen\Screen;

abstract class CrudScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name;

    /**
     * Display header description.
     *
     * @var string
     */
    public $description;

    /**
     * @var ResourceRequest
     */
    public $request;

    /**
     * Permission.
     *
     * @var string|array
     */
    public $permission;

    /**
     * @var Resource
     */
    protected $resource;

    /**
     * CrudScreen constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->request = app(ResourceRequest::class);
            $this->resource = $this->request->resource();
            $this->name = $this->resource::label();
            $this->permission = $this->resource::permission();

            return $next($request);
        });
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param string $abilities
     *
     * @return bool
     */
    public function can(string $abilities): bool
    {
        return $this->request->can($abilities);
    }

    /**
     * Get fields with prefixes
     *
     * @return array|Field[]
     */
    public function fields()
    {
        return array_map(function (Field $field) {
            return $field->set('name', 'model.' . $field->get('name'));
        }, $this->resource->fields());
    }
}
