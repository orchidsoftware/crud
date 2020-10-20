<?php

namespace Orchid\Crud\Builder;

use Illuminate\Support\Str;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

class FieldMigration
{
    private $name;

    private $type;

    private $options;

    private $arguments;

    public $nullable = true;


    public static function make($field):self
    {
        return (new static)->parse($field);
    }

    /**
     * @param $field
     * @return $this
     */
    public function parse($field)
    {
        $this->parseDefault($field);

        if ($this->parseJson($field)) {
            return $this;
        }

        if ($field instanceof Input) {
            $this->parseInput($field);
        }
        if ($field instanceof TextArea) {
            $this->parseTextArea($field);
        }
        if ($field instanceof CheckBox) {
            $this->parseCheckBox($field);
        }
        if ($field instanceof Select) {
            $this->parseSelect($field);
        }
        if ($field instanceof DateTimer) {
            $this->parseDateTimer($field);
        }
        if ($field instanceof Picture) {
            $this->parsePicture($field);
        }
        if ($field instanceof Cropper) {
            $this->parseCropper($field);
        }

        return $this;
    }


    private function parseDefault($field)
    {
        $this->name = $field->get('name');

        $this->options['nullable'] = true;
        if ($field->get('required')) {
            $this->nullable = false;
            $this->options['nullable'] = false;
        }

        $this->type = 'text';
    }


    private function parseJson($field)
    {
        if (Str::contains($this->name, '.')) {
            $this->name = explode('.', $this->name)[0];
            $this->type = 'jsonb';
            $this->options['nullable'] = true;

            return true;
        }

        return false;
    }


    private function parseInput($field)
    {
        $this->type = 'string';

        if ($field->get('type') == 'number') {
            $this->type = 'integer';
        }

        if ($this->type == 'string') {
            if ($field->get('max') > 0) {
                $this->arguments['max'] = $field->get('max');
            }
        }
    }


    private function parseTextArea($field)
    {
        $this->type = 'text';
    }


    private function parseCheckBox($field)
    {
        $this->type = 'boolean';
    }


    private function parseSelect($field)
    {
        $this->type = 'string';
    }


    private function parseDateTimer($field)
    {
        $this->type = 'dateTime';
    }


    private function parsePicture($field)
    {
        $this->type = 'string';
    }

    private function parseCropper($field)
    {
        $this->type = 'string';
    }


    public function getMigration()
    {
        $syntax = sprintf("\$table->%s('%s')", $this->type, $this->name);

        // If there are arguments for the schema type, like string('name', 250)
        if ($this->arguments) {
            $syntax = substr($syntax, 0, -1) . ', ';

            $syntax .= implode(', ', $this->arguments) . ')';
        }

        foreach ($this->options as $method => $value) {
            if ($value) {
                $syntax .= sprintf("->%s(%s)", $method, $value === true ? '' : $value);
            }
        }

        return $syntax .= ';';
    }
}
