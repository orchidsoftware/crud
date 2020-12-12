<?php

namespace Orchid\Crud\Tests;

use Illuminate\Support\Facades\Gate;
use Orchid\Crud\Tests\Fixtures\PostResource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Crud\Tests\Policies\PostProtectedPolicy;

class CrudPolicyTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Post::class, PostProtectedPolicy::class);
    }

    /**
     *
     */
    public function testListResource(): void
    {
        $this->get(route('platform.resource.list', [
            'resource' => PostResource::uriKey(),
        ]))->assertForbidden();
    }

    /**
     *
     */
    public function testCreateResource(): void
    {
        $this->get(route('platform.resource.create', [
            'resource' => PostResource::uriKey(),
        ]))->assertForbidden();
    }
}
