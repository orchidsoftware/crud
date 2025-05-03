<?php

namespace Orchid\Crud;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Orchid\Crud\Exceptions\BehaviourChangers\ErrorHandledMessage;
use Orchid\Crud\Exceptions\BehaviourChangers\InfoMessageChanger;
use Orchid\Crud\Exceptions\BehaviourChangers\RedirectTo;
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
     * @var ResourceRequest
     */
    protected $request;

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

            return $next($request);
        });
    }

    /**
     * The Resource Request instance.
     *
     * @return ResourceRequest
     */
    public function request(): ResourceRequest
    {
        return $this->request;
    }

    /**
     * The name of the screen to be displayed in the header.
     */
    public function name(): ?string
    {
        return $this->resource::label();
    }

    /**
     * A description of the screen to be displayed in the header.
     */
    public function description(): ?string
    {
        return $this->resource::description();
    }

    /**
     * The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return Arr::wrap($this->resource::permission());
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param string     $abilities
     * @param Model|null $model
     *
     * @return bool
     */
    protected function can(string $abilities, ?Model $model = null): bool
    {
        return $this->request->can($abilities, $model);
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
                    ->parameters(array_merge(
                        $action->button()->get('parameters', []),
                        ['_action' => $action->name()]
                    ));
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
            ->icon('bs.three-dots-vertical')
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
        return collect($this->resource->actions($this))->map(function ($action) {
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
     * Overrides the Screen handle method so it can catch ErrorHandledMessage and RedirectTo behaviour changers.
     */
    public function handle(Request $request, ...$arguments)
    {
        try {
            return parent::handle($request, ...$arguments);
        } catch (ErrorHandledMessage $e) {
            Toast::error($e->getMessage());

            return redirect()->back();
        } catch (RedirectTo $e) {
            if ($e->getToastMessage()) {
                Toast::{$e->getToastLevel()}($e->getToastMessage());
            }

            return redirect($e->getRedirectUrl());
        }
    }

    /**
     * @param UpdateRequest $request
     *
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $model = $request->findModelOrFail();

        try {
            $request->resource()->save($request, $model);

            Toast::info($this->resource::updateToastMessage());
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

    /**
     * @param DeleteRequest $request
     *
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function delete(DeleteRequest $request)
    {
        $request->resource()->delete(
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
        $request->resource()->forceDelete(
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
        $request->resource()->restore(
            $model = $request->findModelOrFail()
        );

        Toast::info($this->resource::restoreToastMessage());

        if ($request->resource()::$redirectToViewAfterSaving) {
            return redirect()->route('platform.resource.view', [
                'resource' => $request->resource,
                'id'       => $model->getKey(),
            ]);
        } else {
            return redirect()->route('platform.resource.list', $request->resource);
        }
    }

    /**
     * Determine if the resource is soft deleted.
     *
     * @return bool
     */
    protected function isSoftDeleted(): bool
    {
        if (property_exists($this, 'model')) {
            return $this->resource::softDeletes() && ((object) $this)->model->trashed();
        }

        return false;
    }
}
