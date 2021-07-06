<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\Tests\Fixtures\PostCustomErrorMessageResource;
use Orchid\Crud\Tests\Models\Post;

class CustomMessageValidationTest extends TestCase
{
    public function testCustomMessageForValidationResource(): void
    {
        $post = Post::factory()->make([
            'title' => null,
        ]);

        $this
            ->followingRedirects()
            ->from(route('platform.resource.create', [
                'resource' => PostCustomErrorMessageResource::uriKey(),
            ]))
            ->post(route('platform.resource.create', [
                'resource' => PostCustomErrorMessageResource::uriKey(),
                'method'   => 'save',
            ]), [
                'model' => $post->toArray(),
            ])
            ->assertSee('Поле обязательно для заполнения')
            ->assertSee('Заголовок')
            ->assertOk();
    }
}
