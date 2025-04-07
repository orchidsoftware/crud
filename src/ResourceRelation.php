<?php

namespace Orchid\Crud;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ResourceRelation
{
    protected Collection $relations;

    /**
     * ResourceRelation constructor.
     *
     * @param Resource $resource
     */
    public function __construct(protected Resource $resource)
    {
        $this->relations = collect($this->resource->relations())
            ->map(fn (string $resource) => app($resource))
            ->filter(fn (Resource $resource) => $this->can('viewAll', $resource->getModel()));
    }

    /**
     * Check if the user has the given ability on the model.
     *
     * @param string                                   $abilities
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return bool
     */
    public function can(string $abilities, ?Model $model = null): bool
    {
        if (Gate::getPolicyFor($model) === null) {
            return true;
        }

        /** @var Authorizable|null $user */
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        return $user->can($abilities, $model);
    }

    /**
     * Get the key for the relation, if any.
     *
     * @return string|null
     */
    public function findRelationKey(): ?string
    {
        return request()->route('relation') ?? $this->relations->keys()->first();
    }

    /**
     * Find the corresponding relation resource.
     *
     * @throws ResourceNotFoundException
     *
     * @return \Orchid\Crud\Resource|null
     */
    public function findRelationResource(): ?Resource
    {
        if ($this->relations->isEmpty()) {
            return null;
        }

        $key = $this->findRelationKey();

        return $this->relations->get($key) ?? abort(404, "Relation [{$key}] not found.");
    }

    /**
     * Get the relation pagination list.
     *
     * @param Model $model
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator|array
     */
    public function getRelationPaginationList(Model $model)
    {
        $relation = $this->findRelationKey();
        $resource = $this->findRelationResource();

        if ($resource === null) {
            return [];
        }

        return $model->$relation()
            ->with($resource->with())
            ->filters()
            ->filtersApply($resource->filters())
            ->paginate($resource->perPage());
    }

    /**
     * Get the available relations.
     *
     * @return Collection
     */
    public function available(): Collection
    {
        return $this->relations;
    }
}
