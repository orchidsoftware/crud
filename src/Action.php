<?php

namespace Orchid\Crud;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;

abstract class Action
{
    /**
     * The button of the action.
     *
     * @return Button
     */
    abstract public function button(): Button;

    /**
     * Perform the action on the given models.
     *
     * @param \Illuminate\Support\Collection $models
     */
    abstract public function handle(Collection $models);

    /**
     * Unique method string to use in the request
     *
     * @return string
     */
    public static function name(): string
    {
        return Str::of(static::class)->replace('\\', '-')->slug();
    }
}
