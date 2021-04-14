<?php

namespace Orchid\Crud\Requests;

use Illuminate\Support\Collection;
use Orchid\Crud\ResourceRequest;

class ActionRequest extends ResourceRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return  [
            '_action' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @return void
     */
    public function withValidator()
    {
        return null;
    }

    /**
     * @return Collection
     */
    public function models(): Collection
    {
        $models = collect();

        if ($this->has('_models')) {
            $models = $this->model()->whereIn(
                $this->model()->getKeyName(),
                $this->get('_models')
            )->get();
        }

        $current = $this->findModel();

        if ($current !== null) {
            $models->push($current);
        }

        return $models;
    }
}
