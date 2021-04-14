<?php


namespace Orchid\Crud;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Orchid\Crud\Requests\ActionRequest;
use Orchid\Crud\Requests\DeleteRequest;
use Orchid\Crud\Requests\ForceDeleteRequest;
use Orchid\Crud\Requests\RestoreRequest;
use Orchid\Crud\Requests\UpdateRequest;
use Orchid\Screen\Action as ActionButton;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

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
     * @return Collection
     */
    protected function availableActions(): Collection
    {
        return $this->actions()
            ->map(function (Action $action) {
                return $action->button()
                    ->method('action')
                    ->parameters(['_action' => $action->name()]);
            })
            ->filter(function (ActionButton $action) {
                return $action->isSee();
            });
    }

    /**
     * @return DropDown
     */
    protected function actionsButtons(): DropDown
    {
        $actions = $this->availableActions();

        return DropDown::make('Actions')
            ->icon('options-vertical')
            ->canSee($actions->isNotEmpty())
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
        return collect($this->resource->actions())->map(function ($action) {
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
        $models = $request->models();

        if ($models->isEmpty()) {
            Toast::warning($request->resource()->emptyResourceForAction());

            return back();
        }

        /** @var Action $action */
        $action = $this->actions()
            ->filter(function (Action $action) use ($request) {
                return $action->name() === $request->query('_action');
            })->whenEmpty(function () {
                abort(405);
            })->first();

        return $action->handle($request->models());
    }

    /**
     * @param UpdateRequest $request
     *
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $request->resource()->onSave($request, $request->findModelOrFail());

        Toast::info($this->resource::updateToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param DeleteRequest $request
     *
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function delete(DeleteRequest $request)
    {
        $request->resource()->onDelete(
            $request->findModelOrFail()
        );

        Toast::info($this->resource::deleteToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param ForceDeleteRequest $request
     *
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function forceDelete(ForceDeleteRequest $request)
    {
        $request->resource()->onForceDelete(
            $request->findModelOrFail()
        );

        Toast::info($this->resource::deleteToastMessage());

        return redirect()->route('platform.resource.list', $request->resource);
    }

    /**
     * @param RestoreRequest $request
     *
     * @return RedirectResponse
     */
    public function restore(RestoreRequest $request)
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
    protected function isSoftDeleted(): bool
    {
        return $this->resource::softDeletes() && $this->model->trashed();
    }
}
