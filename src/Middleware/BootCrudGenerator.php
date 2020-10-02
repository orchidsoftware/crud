<?php

namespace Orchid\Crud\Middleware;

use Orchid\Crud\Arbitrator;
use Orchid\Crud\ResourceFinder;

class BootCrudGenerator
{
    /**
     * @var ResourceFinder
     */
    protected $finder;

    /**
     * @var Arbitrator
     */
    protected $arbitrator;

    /**
     * BootCrudGenerator constructor.
     *
     * @param ResourceFinder $finder
     * @param Arbitrator     $arbitrator
     */
    public function __construct(ResourceFinder $finder, Arbitrator $arbitrator)
    {
        $this->finder = $finder;
        $this->arbitrator = $arbitrator;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        $resources = $this->finder
            ->setNamespace(app()->getNamespace() . 'Orchid\\Resources')
            ->find(app_path('Orchid/Resources'));

        $this->arbitrator
            ->resources($resources)
            ->boot();

        return $next($request);
    }
}
