<?php

namespace Orchid\Crud\Tests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Orchid\Crud\Tests\Fixtures\PostResource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Crud\Tests\Policies\PostProtectedPolicy;
use Orchid\Platform\Models\User;
use Orchid\Support\Facades\Dashboard;

class CrudPolicyTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Post::class, PostProtectedPolicy::class);

        $this->loginWithFakeUser();
    }

    /**
     *
     */
    public function loginWithFakeUser()
    {
        $user = new User([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $user->permissions = Dashboard::getAllowAllPermission();
        $this->be($user);
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
