<?php

namespace Orchid\Crud\Tests\Builder;

use Orchid\Crud\Builder\FieldMigration;
use Orchid\Crud\Tests\Fixtures\AllFieldResource;
use Orchid\Crud\Tests\TestCase;

class FieldsTest extends TestCase
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resource = app(AllFieldResource::class);
    }

    /**
     * test for  Input Field
     */
    public function testInputField(): void
    {
        $field = $this->resource->fields()[0];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->string(\'inputname\');', $migration);


        $field->required(false);
        $migration = FieldMigration::make($field)->getMigration();
        $this->assertEquals('$table->string(\'inputname\')->nullable();', $migration);

        $field->max(255);
        $migration = FieldMigration::make($field)->getMigration();
        $this->assertEquals('$table->string(\'inputname\', 255)->nullable();', $migration);

        $field->type('number');
        $migration = FieldMigration::make($field)->getMigration();
        $this->assertEquals('$table->integer(\'inputname\')->nullable();', $migration);
    }

    /**
     * test for  TextArea Field
     */
    public function testTextAreaField(): void
    {
        $field = $this->resource->fields()[1];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->text(\'textareaname\');', $migration);
    }

    /**
     * test for  CheckBox Field
     */
    public function testCheckBoxField(): void
    {
        $field = $this->resource->fields()[2];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->boolean(\'checkboxname\')->nullable();', $migration);
    }

    /**
     * test for  CheckBox Field
     */
    public function testSelectField(): void
    {
        $field = $this->resource->fields()[3];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->string(\'selectname\')->nullable();', $migration);
    }

    /**
     * test for  DateTimer Field
     */
    public function testDateTimerField(): void
    {
        $field = $this->resource->fields()[4];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->dateTime(\'datetimername\')->nullable();', $migration);
    }

    /**
     * test for  Picture Field
     */
    public function testPictureField(): void
    {
        $field = $this->resource->fields()[5];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->string(\'picturename\')->nullable();', $migration);
    }

    /**
     * test for  Json Field
     */
    public function testJsonField(): void
    {
        $field = $this->resource->fields()[6];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->jsonb(\'manyname\')->nullable();', $migration);
    }

    /**
     * test for  Json Field
     */
    public function testJsonRequiredField(): void
    {
        $field = $this->resource->fields()[7];

        $migration = FieldMigration::make($field)->getMigration();
        $this->assertIsString($migration);
        $this->assertEquals('$table->jsonb(\'manyname\')->nullable();', $migration);
    }
}
