<?php


namespace Orchid\Crud\Tests;

use Orchid\Crud\ResourceRoute;
use Orchid\Crud\Tests\Fixtures\NoDisplayInNavigationResource;
use Orchid\Crud\Tests\Fixtures\PostResource;

class NavigationTest extends TestCase
{
    /**
     *
     */
    public function testNoDisplayResourceInNavigation(): void
    {
        $this->get(route(ResourceRoute::LIST->name(), [
            'resource' => PostResource::uriKey(),
        ]))
            ->assertSee(PostResource::singularLabel())
            ->assertDontSee(NoDisplayInNavigationResource::singularLabel())
            ->assertOk();
    }
}
