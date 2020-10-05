<?php


namespace Orchid\Crud;

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
     * @param ResourceRequest $resourceRequest
     */
    public function __construct(ResourceRequest $resourceRequest)
    {
        $this->middleware(function ($request, $next) use ($resourceRequest) {
            $this->resource = $resourceRequest->resource();
            $this->name = $this->resource::label();
            $this->permission = $this->resource::uriKey();

            return $next($request);
        });
    }
}
