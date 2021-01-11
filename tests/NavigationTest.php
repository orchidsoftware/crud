<?php


namespace Orchid\Crud\Tests;

use Orchid\Crud\Tests\Fixtures\NoDisplayInNavigationResource;
use Orchid\Crud\Tests\Fixtures\PostResource;

class NavigationTest extends TestCase
{
    /**
     *
     */
    public function testNoDisplayResourceInNavigation(): void
    {
        $this->get(route('platform.resource.list', [
            'resource' => PostResource::uriKey(),
        ]))
            ->assertSee(PostResource::singularLabel())
            ->assertDontSee(NoDisplayInNavigationResource::singularLabel())
            ->assertOk();
    }
}
