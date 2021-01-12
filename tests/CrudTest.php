<?php

namespace Orchid\Crud\Tests;

use Illuminate\Support\Str;
use Orchid\Crud\Tests\Fixtures\PostResource;
use Orchid\Crud\Tests\Models\Post;

class CrudTest extends TestCase
{
    /**
     * @var Post[]
     */
    protected $posts;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->posts = Post::factory()->count(50)->create();
    }

    /**
     *
     */
    public function testListResource(): void
    {
        $this->get(route('platform.resource.list', [
            'resource' => PostResource::uriKey(),
        ]))
            ->assertSee(PostResource::singularLabel())
            ->assertSee(PostResource::createButtonLabel())
            ->assertSee('Edit')
            ->assertSeeText($this->posts->first()->description)
            ->assertOk();
    }

    /**
     *
     */
    public function testCreateResource(): void
    {
        $this->get(route('platform.resource.create', [
            'resource' => PostResource::uriKey(),
        ]))
            ->assertSee(PostResource::createButtonLabel())
            ->assertSee('A string containing the name text and design to attract attention')
            ->assertOk();
    }

    /**
     *
     */
    public function testCreateActionResource(): void
    {
        $post = Post::factory()->make();

        $this
            ->followingRedirects()
            ->post(route('platform.resource.create', [
                'resource' => PostResource::uriKey(),
                'method'   => 'save',
            ]), $post->toArray())
            ->assertSee(PostResource::createToastMessage())
            ->assertOk();

        $this->get(route('platform.resource.edit', [
            'resource' => PostResource::uriKey(),
            'id'       => Post::orderBy('id', 'desc')->first(),
        ]))
            ->assertSee(PostResource::updateButtonLabel())
            ->assertSee($post->title)
            ->assertSee($post->description)
            ->assertSee($post->body)
            ->assertOk();
    }

    /**
     *
     */
    public function testCreateActionRulesResource(): void
    {
        $post = Post::factory()->make([
            'title' => 'unique title',
        ]);

        $this
            ->followingRedirects()
            ->post(route('platform.resource.create', [
                'resource' => PostResource::uriKey(),
                'method'   => 'save',
            ]), $post->toArray())
            ->assertSee(PostResource::createToastMessage())
            ->assertOk();

        $post = Post::factory()->make([
            'title' => 'unique title',
        ]);

        $this
            ->followingRedirects()
            ->post(route('platform.resource.create', [
                'resource' => PostResource::uriKey(),
                'method'   => 'save',
            ]), $post->toArray())
            ->assertSee('The title has already been taken.')
            ->assertSee('Change a few things up and try submitting again.')
            ->assertOk();
    }

    /**
     *
     */
    public function testEditResource(): void
    {
        $post = $this->posts->first();

        $this->get(route('platform.resource.edit', [
            'resource' => PostResource::uriKey(),
            'id'       => $post,
        ]))
            ->assertSee(PostResource::updateButtonLabel())
            ->assertSee($post->title)
            ->assertSee($post->description)
            ->assertSee($post->body)
            ->assertOk();
    }

    /**
     *
     */
    public function testUpdateActionResource(): void
    {
        $post = $this->posts->first();

        $post->description = Str::random();

        $this
            ->followingRedirects()
            ->post(route('platform.resource.edit', [
                'resource' => PostResource::uriKey(),
                'id'       => $post,
                'method'   => 'update',
            ]), $post->toArray())
            ->assertSee(PostResource::updateToastMessage())
            ->assertOk();

        $this->get(route('platform.resource.edit', [
            'resource' => PostResource::uriKey(),
            'id'       => $post,
        ]))
            ->assertSee($post->description)
            ->assertOk();
    }

    /**
     *
     */
    public function testDeleteActionResource(): void
    {
        $post = $this->posts->first();

        $this
            ->followingRedirects()
            ->post(route('platform.resource.edit', [
                'resource' => PostResource::uriKey(),
                'id'       => $post,
                'method'   => 'delete',
            ]))
            ->assertSee(PostResource::deleteToastMessage())
            ->assertOk();

        $this->get(route('platform.resource.edit', [
            'resource' => PostResource::uriKey(),
            'id'       => $post,
        ]))
            ->assertNotFound();
    }
}
