<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\Tests\Fixtures\DescriptionResource;

class DescriptionTest extends TestCase
{
    /**
     *
     */
    public function testListResource(): void
    {
        $this->get(route('platform.resource.list', [
            'resource' => DescriptionResource::uriKey(),
        ]))
            ->assertSee(DescriptionResource::description());
    }
}
