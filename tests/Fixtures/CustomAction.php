<?php

namespace Orchid\Crud\Tests\Fixtures;

use Illuminate\Support\Collection;
use Orchid\Crud\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class CustomAction extends Action
{
    /**
     * The button of the action.
     *
     * @return Button
     */
    public function button(): Button
    {
        return Button::make('Run Action')
            ->parameters([
                '_action_url_param' => 'should_be_preserved',
                '_action'           => 'should_be_overridden',
            ])
            ->icon('bs.fire');
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Illuminate\Support\Collection $models
     */
    public function handle(Collection $models)
    {
        $models->each(function () {
            // action
        });

        Toast::message('It worked!');
    }
}
