<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\Tests\Fixtures\CustomAction;
use Orchid\Crud\Tests\Fixtures\PostActionResource;
use Orchid\Crud\Tests\Fixtures\PostNoActionResource;
use Orchid\Crud\Tests\Models\Post;
use Orchid\Screen\Fields\CheckBox;

class ActionTest extends TestCase
{
    /**
     * @var Post[]
     */
    protected $posts;

    /**
     * @var CheckBox
     */
    protected $checkbox;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->posts = Post::factory()->count(5)->create();

        $this->checkbox = CheckBox::make('_models[]')
            ->value($this->posts->first()->getKey())
            ->checked(false);
    }

    public function testShowActionOnListResource(): void
    {
        $this->get(route('platform.resource.list', [
            'resource' => PostActionResource::uriKey(),
        ]))
            ->assertSee('Run Action')
            ->assertSee($this->checkbox, false)
            ->assertOk();
    }

    public function testDontShowActionOnListResource(): void
    {
        $this->get(route('platform.resource.list', [
            'resource' => PostNoActionResource::uriKey(),
        ]))
            ->assertDontSee('Run Action')
            ->assertDontSee($this->checkbox, false)
            ->assertOk();
    }

    public function testRunActionWithEmptyResource():void
    {
        $this
            ->from(route('platform.resource.list', [
                'resource' => PostActionResource::uriKey(),
            ]))
            ->followingRedirects()
            ->post(route('platform.resource.list', [
                'resource'  => PostActionResource::uriKey(),
                'method'    => 'action',
                '_action'   => CustomAction::name(),
            ]))
            ->assertSee(PostActionResource::emptyResourceForAction())
            ->assertOk();
    }

    public function testRunActionWithResource():void
    {
        $this
            ->from(route('platform.resource.list', [
                'resource' => PostActionResource::uriKey(),
            ]))
            ->followingRedirects()
            ->post(route('platform.resource.list', [
                'resource' => PostActionResource::uriKey(),
                'method'   => 'action',
                '_action'  => CustomAction::name(),
                '_models'  => $this->posts->map->getKey()->toArray(),
            ]))
            ->assertSee('It worked')
            ->assertOk();
    }
}
