<?php

namespace Orchid\Crud;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Orchid\Crud\Layouts\ResourceFields;

class ResourceRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return  [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return collect($this->model)->keys()
            ->mapWithKeys(function ($key) {
                return [$key => $key];
            })
            ->merge($this->resource()->attributes())
            ->mapWithKeys(function ($value, $key) {
                return [ResourceFields::PREFIX . '.' . $key => $value];
            })
            ->toArray();
    }

    /**
     * Validate the class instance.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages():array
    {
        return collect($this->resource()->messages())
            ->mapWithKeys(function ($value, $key) {
                return [ResourceFields::PREFIX . '.' . $key => $value];
            })->toArray();
    }

    /**
     * Configure the validator instance.
     *
     * @return void
     */
    public function withValidator()
    {
        $data = Arr::wrap($this->model);

        // Remove private parameters (Start with '_')
        collect($this->query->all())
            ->keys()
            ->filter(function (string $key) {
                return Str::startsWith($key, '_');
            })->each(function (string $key) {
                $this->query->remove($key);
            });

        collect($this->all())
            ->keys()
            ->each(function (string $key) {
                $this->offsetUnset($key);
            });

        $this->replace($data);
    }

    /**
     * Find the resource instance for the request.
     *
     * @return Resource
     */
    public function resource(): Resource
    {
        return $this->arbitrator()->findOrFail(
            $this->route('resource')
        );
    }

    /**
     * @return Arbitrator
     */
    public function arbitrator(): Arbitrator
    {
        return app(Arbitrator::class);
    }

    /**
     * @return Model
     */
    public function model(): Model
    {
        return $this->resource()->getModel();
    }

    /**
     * Find the model instance for the request.
     *
     * @return Model|null
     */
    public function findModel(): ?Model
    {
        return $this->getModelQuery()->find($this->route('id'));
    }

    /**
     * Find the model instance for the request.
     *
     * @return Model
     */
    public function findModelOrFail()
    {
        return $this->getModelQuery()->findOrFail($this->route('id'));
    }

    /**
     * @return Paginator
     */
    public function getModelPaginationList()
    {
        $query = $this->resource()->paginationQuery($this, $this->model());

        return $query
            ->with($this->resource()->with())
            ->filters()
            ->filtersApply($this->resource()->filters())
            ->paginate($this->resource()->perPage());
    }

    /**
     * @return Model
     */
    protected function getModelQuery()
    {
        $query = $this->resource()->modelQuery($this, $this->model());

        if ($this->resource()->softDeletes()) {
            $query = $query->withTrashed();
        }

        return $query->with($this->resource()->with());
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param string     $abilities
     * @param Model|null $model
     *
     * @return bool
     */
    public function can(string $abilities, ?Model $model = null): bool
    {
        if ($model === null) {
            $model = $this->route('id') === null
                ? $this->model()
                : $this->findModelOrFail();
        }

        if (Gate::getPolicyFor($model) === null) {
            return true;
        }

        /** @var Authorizable|null $user */
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        return $this->user()->can($abilities, $model);
    }
}
