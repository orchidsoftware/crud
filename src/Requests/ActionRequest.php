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

        $modelsKey = $this->modelsKey();

        if ($this->has($modelsKey)) {
            $models = $this->getModelQuery()->findMany(
                $this->get($modelsKey),
            );
        }

        $current = $this->findModel();

        if ($current !== null) {
            $models->push($current);
        }

        return $models;
    }

    public function isRelationAction(): bool
    {
        return $this->has('_relation_models');
    }

    private function modelsKey(): string
    {
        return $this->isRelationAction() ? '_relation_models' : '_models';
    }
}
