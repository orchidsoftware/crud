<?php

namespace Orchid\Crud\Tests;

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
            ->assertSee('Resources')
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
    public function testEditResource(): void
    {
        $post = $this->posts->first();

        $this->get(route('platform.resource.edit', [
            'resource' => PostResource::uriKey(),
            'id' => $post,
        ]))
            ->assertSee(PostResource::updateButtonLabel())
            ->assertSee($post->title)
            ->assertSee($post->description, false)
            ->assertSee($post->body, false)
            ->assertOk();
    }
}
