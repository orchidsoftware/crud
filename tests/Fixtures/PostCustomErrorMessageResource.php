<?php

namespace Orchid\Crud\Tests\Fixtures;

class PostCustomErrorMessageResource extends PostResource
{
    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Поле обязательно для заполнения',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'Заголовок',
        ];
    }
}
