<?php

namespace Orchid\Crud\Builder;

use Illuminate\Support\Str;
use Orchid\Screen\Fields\Upload;

class Migrations
{

    private $fields = [];

    private $migration  = [];


    public static function make($field):self
    {
        return (new static)->build($field);
    }


    public function build($fields)
    {
        return $this->buildFields($fields)->createMigrations();
    }

    /**
     * Bulild fields from resurce to migration
     *
     * @param $fields
     * @return $this
     */
    private function buildFields($fields) {
        foreach ($fields as $field) {
            if ($field instanceof Upload) {
                continue;
            }
            $this->fields[$field->get('name')] = FieldMigration::make($field);
        }

        return $this;
    }

    /**
     * Create migrations strings from fields
     *
     * @return $this
     */
    private function createMigrations() {
        foreach ($this->fields as $name => $migration) {
            if (Str::contains($name, '.')) {
                $name = explode('.',$name)[0];
            }

            $this->migration[$name] = $migration->getMigration();
        }

        return $this;
    }

    /**
     * Get array with migration strings
     *
     * @return array
     */
    public function getMigration(): array
    {
        return $this->migration;
    }

}