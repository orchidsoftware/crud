<?php

namespace Orchid\Crud\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Orchid\Crud\Arbitrator;
use Orchid\Crud\Resource;
use Orchid\Crud\Tests\Fixtures\PostResource;
use Orchid\Crud\Tests\Fixtures\PrivateResource;
use Orchid\Crud\Tests\FixturesBad\WithoutModel;
use Orchid\Crud\Tests\FixturesBad\WithoutTraits;
use Orchid\Platform\ItemPermission;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Field;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
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

        View::callComposer(\view('platform::dashboard'));

        /** @var Collection $menu */
        $menu = app(\Orchid\Platform\Dashboard::class)->menu[\Orchid\Platform\Dashboard::MENU_MAIN];

        $existName = $menu->filter(function (Menu $menu) {
            return $menu->get('name') === PostResource::label();
        })->isNotEmpty();

        $this->assertTrue($existName);

        $existUri = $menu->filter(function (Menu $menu) {
            return $menu->get('href') === \route('platform.resource.list', [
                    'resource' => PostResource::uriKey(),
                ]);
        })->isNotEmpty();

        $this->assertTrue($existUri);
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

    /**
     *
     */
    public function testBootRegisterExistingPermissionResource(): void
    {
        Dashboard::registerPermissions(
            ItemPermission::group('Other')
                ->addPermission(PrivateResource::permission(), PrivateResource::label())
        );

        $this->arbitrator->boot();

        $permission = Dashboard::getPermission()->get('CRUD');

        $this->assertNull($permission);
    }

    public function testErrorMessageForResourceWithoutModel(): void
    {
        $arbitrator = app(Arbitrator::class);

        $this->expectExceptionMessage('The resource "Orchid\Crud\Tests\FixturesBad\WithoutModel" must specify the Eloquent class to generate.');

        $arbitrator->resources([
            WithoutModel::class
        ]);
    }

    public function testErrorMessageForResourceWithoutTraits(): void
    {
        $arbitrator = app(Arbitrator::class);

        $this->expectExceptionMessage('The model "Orchid\Crud\Tests\Models\PostWithoutTraits" must have the required orchid/platform traits.');

        $arbitrator->resources([
            WithoutTraits::class
        ]);
    }
}
