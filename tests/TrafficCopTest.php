<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\Tests\Fixtures\PostResource;
use Orchid\Crud\Tests\Models\Post;

class TrafficCopTest extends TestCase
{
    public function testBaseCopResource(): void
    {
        $post = Post::factory()->create();
        $post->touch();
        $retrievedAt = $post->updated_at->subMinutes(5)->toJson();

        $this
            ->followingRedirects()
            ->from(route('platform.resource.edit', [
                'resource' => PostResource::uriKey(),
                'id'       => $post,
            ]))
            ->post(route('platform.resource.edit', [
                'resource' => PostResource::uriKey(),
                'id'       => $post,
                'method'   => 'update',
                '_retrieved_at' => $retrievedAt,
            ]), [
                'model'         => $post->toArray(),
            ])
            ->assertSee(PostResource::trafficCopMessage())
            ->assertOk();
    }


    public function testEditSuccessCopResource(): void
    {
        $post = Post::factory()->create();
        $post->touch();
        $retrievedAt = $post->updated_at->addMinutes(5)->toJson();

        $this
            ->followingRedirects()
            ->post(route('platform.resource.edit', [
                'resource' => PostResource::uriKey(),
                'id'       => $post,
                'method'   => 'update',
                '_retrieved_at' => $retrievedAt,
            ]), [
                'model'         => $post->toArray(),
            ])
            ->assertDontSee(PostResource::trafficCopMessage())
            ->assertOk();
    }

    /**
     * Set the URL of the previous request.
     *
     * @param string $url
     *
     * @return $this
     */
    public function from(string $url)
    {
        session()->setPreviousUrl($url);

        return $this;
    }
}
