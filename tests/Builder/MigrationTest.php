<?php

namespace Orchid\Crud\Tests\Builder;

use Orchid\Crud\Builder\Migrations;
use Orchid\Crud\Tests\Fixtures\AllFieldResource;
use Orchid\Crud\Tests\TestCase;

class MigrationTest extends TestCase
{
    /**
     * @var Resource
     */
    protected $resource;

    protected $migration;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resource = app(AllFieldResource::class);
        $this->migration = Migrations::make($this->resource->fields());
    }

    /**
     * test for  Input Field
     */
    public function testMigrations(): void
    {
        $migrations = $this->migration->getMigration();

        $this->assertIsArray($migrations);
        $this->assertCount(7, $migrations);
    }
}
