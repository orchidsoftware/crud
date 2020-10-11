<?php


namespace Orchid\Crud;

use Orchid\Crud\Middleware\BootCrudGenerator;
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
            $this->resource = app(ResourceRequest::class)->resource();
            $this->name = $this->resource::label();
            $this->permission = $this->resource::uriKey();

            return $next($request);
        });

    }
}
