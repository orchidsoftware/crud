<?php

namespace Orchid\Crud\Tests;

use Illuminate\Support\Str;
use Orchid\Crud\Arbitrator;
use Orchid\Crud\Tests\Fixtures\PostResource;
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

        $resources = $this
            ->getResourceFinder()
            ->setNamespace('Orchid\Crud\Tests\Fixtures')
            ->find(__DIR__ . '/Fixtures');

        $this->arbitrator = (new Arbitrator())->resources($resources);
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
    public function testBootRegisterResource():void
    {
        $this->arbitrator->boot();
    }
}
