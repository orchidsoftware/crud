<?php

namespace Orchid\Crud\Layouts;

use Illuminate\Support\Facades\Crypt;
use Orchid\Crud\CrudScreen;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Dashboard;

abstract class ResourceListener extends Listener
{
    protected function asyncRoute(): ?string
    {
        $screen = Dashboard::getCurrentScreen();

        if (! $screen) {
            return null;
        }

        if ($screen instanceof CrudScreen) {
            return route(
                'platform.async.listener.crud',
                array_filter(
                    [
                        'screen'   => Crypt::encryptString(get_class($screen)),
                        'layout'   => Crypt::encryptString(static::class),
                        'resource' => Crypt::encryptString(request()->route('resource')),
                        'id'       => ($resourceId = request()->route('id')) ? Crypt::encryptString($resourceId) : null,
                    ]
                )
            );
        } else {
            return route('platform.async.listener', [
                'screen' => Crypt::encryptString(get_class($screen)),
                'layout' => Crypt::encryptString(static::class),
            ]);
        }
    }
}
