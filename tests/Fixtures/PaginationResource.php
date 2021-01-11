<?php

namespace Orchid\Crud\Tests\Fixtures;

class PaginationResource extends PostResource
{
    /**
     * Get the number of models to return per page
     *
     * @return int
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function perPage(): int
    {
        return 3;
    }
}
