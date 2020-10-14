<?php

namespace Orchid\Crud\Tests;

use Illuminate\Support\Str;
use Orchid\Crud\Arbitrator;
use Orchid\Crud\Tests\Fixtures\PostResource;
use Orchid\Platform\Menu;
use Orchid\Support\Facades\Dashboard;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArbitratorTest extends TestCase
{

    /**
     * @var Arbitrator
     */
    protected $arbitrator;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->arbitrator = app(Arbitrator::class);
    }

    /**
     *
     */
    public function testFindArbitrator(): void
    {
        $this->assertInstanceOf(PostResource::class, $this->arbitrator->find('post-resources'));
        $this->assertNull($this->arbitrator->find(Str::random()));
    }

    /**
     *
     */
    public function testFindOrFailArbitrator(): void
    {
        $this->assertInstanceOf(PostResource::class, $this->arbitrator->findOrFail('post-resources'));

        $this->expectException(NotFoundHttpException::class);
        $this->arbitrator->findOrFail(Str::random());
    }

    /**
     *
     */
    public function testBootRegisterMenuResource(): void
    {
        $this->arbitrator->boot();

        /** @var Menu $menu */
        $menu = Dashboard::menu();

        $slug = Str::lower(PostResource::label());

        $this->assertTrue($menu->container->has($slug));

        $this->assertEquals($menu->container->get($slug)['arg']['route'], \route('platform.resource.list', [
            'resource' => PostResource::uriKey(),
        ]));
    }

    /**
     *
     */
    public function testBootRegisterPermissionResource(): void
    {
        $this->arbitrator->boot();

        $permission = Dashboard::getPermission()->get('CRUD');

        $this->assertEquals([
            ["slug" => "private-resource", "description" => "Privates"],
        ], $permission);
    }
}
