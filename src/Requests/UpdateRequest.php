<?php

namespace Orchid\Crud\Requests;

use Carbon\Carbon;
use Orchid\Crud\Resource;

class UpdateRequest extends CreateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->can('update');
    }

    /**
     * Validate the class instance.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if ($this->hasBeenUpdatedSinceRetrieval()) {
            $this->redirectWithTrafficCop();
        }
    }

    /**
     * Determine if the model has been updated since it was retrieved.
     *
     * @return bool
     */
    protected function hasBeenUpdatedSinceRetrieval(): bool
    {
        if ($this->missing('_retrieved_at')) {
            return false;
        }

        /** @var Resource $resource */
        $resource = $this->resource();

        // Check to see whether Traffic Cop is enabled for this resource...
        if ($resource::trafficCop() === false) {
            return false;
        }

        $model = $this->findModelOrFail();
        $column = $model->getUpdatedAtColumn();

        if (! $model->{$column}) {
            return false;
        }

        return $model->{$column}->gt(Carbon::parse($this->input('_retrieved_at')));
    }

    /**
     * Return the user back page with an error
     */
    protected function redirectWithTrafficCop(): void
    {
        $back = back()
            ->withErrors($this->resource()->trafficCopMessage())
            ->withInput();

        abort($back);
    }
}
