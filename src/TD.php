<?php

namespace Orchid\Crud;

use Closure;
use Orchid\Screen\TD as Base;

class TD extends Base
{
    public $queryClosure = null;

    public function query(Closure $queryClosure)
    {
        $this->queryClosure = $queryClosure;
        return $this;
    }

    public function getColumn()
    {
        return $this->column;
    }
}
