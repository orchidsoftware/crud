<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\Tests\Fixtures\PaginationResource;
use Orchid\Crud\Tests\Models\Post;

class PaginationTest extends TestCase
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

        $this->posts = Post::factory()->count(4)->create();
    }

    /**
     *
     */
    public function testListResource(): void
    {
        $response = $this->get(route('platform.resource.list', [
            'resource' => PaginationResource::uriKey(),
        ]));


        $this->posts->take(3)->each(function (Post $post) use ($response) {
            $response->assertSeeText($post->description);
        });

        $response->assertDontSeeText($this->posts->last()->description);
    }
}
