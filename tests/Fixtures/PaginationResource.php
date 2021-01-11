<?php

namespace Orchid\Crud\Tests\Fixtures;

class PaginationResource extends PostResource
{
    /**
     * Get the number of models to return per page
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return int
     */
    public static function perPage(): int
    {
        return 3;
    }
}
