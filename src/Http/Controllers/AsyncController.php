<?php

namespace Orchid\Crud\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Orchid\Crud\CrudScreen;
use Orchid\Platform\Http\Controllers\AsyncController as OrchidAsyncController;

class AsyncController extends OrchidAsyncController
{
    public function crudListener(Request $request, string $screen, string $layout, string $resource, ?string $id = null)
    {
        $screen = Crypt::decryptString($screen);
        $layout = Crypt::decryptString($layout);
        $resource = Crypt::decryptString($resource);
        $id = $id ? Crypt::decryptString($id) : null;

        // Fill the request resource and ID
        $request->route()->setParameter('resource', $resource);
        $request->route()->setParameter('id', $id);

        // This allows us to use the same listener view without needing to copy it to this package
        $request->route()->action['as'] = 'platform.async.listener';

        /** @var \Orchid\Crud\CrudScreen $screen */
        $screen = app($screen);

        if (! $screen instanceof CrudScreen) {
            return abort(500, 'Screen is not a CrudScreen');
        }

        // Boot middleware so it can fill the request and resource
        foreach ($screen->getMiddleware() as $middlewareConfig) {
            $screenMiddleware = $middlewareConfig['middleware'];

            $screenMiddleware($request, function ($newRequest) use (&$request) {
                $request = $newRequest;
            });
        }

        /** @var \Orchid\Screen\Layouts\Listener $layout */
        $layout = app($layout);

        return $screen->asyncParticalLayout($layout, $request);
    }
}
