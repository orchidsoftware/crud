<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\ResourceRoute;
use Orchid\Crud\Tests\Fixtures\DescriptionResource;

class DescriptionTest extends TestCase
{
    /**
     *
     */
    public function testListResource(): void
    {
        $this->get(route(ResourceRoute::LIST->name(), [
            'resource' => DescriptionResource::uriKey(),
        ]))
            ->assertSee(DescriptionResource::description());
    }
}
