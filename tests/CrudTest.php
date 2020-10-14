<?php

namespace Orchid\Crud\Tests;

use Orchid\Crud\Arbitrator;
use Orchid\Crud\Tests\Fixtures\PostResource;

class CrudTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        app(Arbitrator::class)->boot();
    }

    public function testListResource(): void
    {
        $test = $this->get(route('platform.resource.list', [
            'resource' => PostResource::uriKey(),
        ]))
            ->assertOk();
        //->assertSee('Resources')
        //->assertSee('Posts')
        //->assertSee('Create Post');


        //$this->assertTrue(true);

        file_put_contents(__DIR__. '/test.html', $test->getContent());
        //dd($test);
    }
}
