<?php

namespace Orchid\Crud\Concerns;

use Orchid\Crud\Arbitrator;
use Orchid\Crud\Resource;

trait Arbitrable
{
    /**
     * @param string $key
     *
     * @return Resource
     */
    protected function arbitrationFindOrFail(string $key): Resource
    {
        return app(Arbitrator::class)->findOrFail($key);
    }
}
