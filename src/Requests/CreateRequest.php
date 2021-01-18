<?php

namespace Orchid\Crud\Requests;

use Orchid\Crud\Layouts\ResourceFields;
use Orchid\Crud\ResourceRequest;

class CreateRequest extends ResourceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->can('create');
    }

    /**
     * @return array
     */
    public function rules()
    {
        if ($this->method() === 'GET') {
            return [];
        }

        $model = $this->findModel() ?? $this->resource()->getModel();
        $rules = $this->resource()->rules($model);

        return collect($rules)
            ->mapWithKeys(function ($value, $key) {
                return [ResourceFields::PREFIX . '.' . $key => $value];
            })
            ->toArray();
    }
}
