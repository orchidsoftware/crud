<?php

namespace Orchid\Crud\Requests;

use Orchid\Crud\ResourceRequest;

class ViewRequest extends ResourceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->can('view');
    }
}
