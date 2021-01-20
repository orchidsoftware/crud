<?php


namespace Orchid\Crud;

use Illuminate\Support\Collection;
use Orchid\Crud\Requests\ActionRequest;
use Orchid\Screen\Actions\DropDown;
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
            $this->description = $this->resource::description();
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
    protected function can(string $abilities): bool
    {
        return $this->request->can($abilities);
    }

    /**
     * @return DropDown
     */
    protected function actionsButtons(): DropDown
    {
        $actions = $this->actions()
            ->map(function (Action $action) {
                return $action->button()
                    ->method('action')
                    ->parameters(['_action' => $action->name()]);
            });

        return DropDown::make('Actions')
            ->icon('options-vertical')
            ->list($actions->toArray());
    }

    /**
     * @return Collection
     */
    protected function actionsMethods()
    {
        return $this->actions()->map(function (Action $action) {
            return $action->name();
        });
    }

    /**
     * @return Collection
     */
    protected function actions(): Collection
    {
        return collect($this->resource->actions())->map(function ($action){
           return is_string($action) ? resolve($action) : $action;
        });
    }

    /**
     * @param ActionRequest $request
     *
     * @return mixed
     */
    public function action(ActionRequest $request)
    {
        /** @var Action $action */
        $action = $this->actions()
            ->filter(function (Action $action) use ($request) {
                return $action->name() === $request->query('_action');
            })->whenEmpty(function () {
                abort(405);
            })->first();

        return $action->handle($request->models());
    }

}
