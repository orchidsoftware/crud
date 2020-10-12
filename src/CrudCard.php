<?php

namespace Orchid\Crud;

use Orchid\Screen\Contracts\Cardable;
use Orchid\Support\Color;

class CrudCard implements Cardable
{
    public $title;
    public $description;
    public $image;
    public $color;
    public $status;

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function image(): ?string
    {
        return $this->image ?? null;
    }

    public function color(): ?Color
    {
        return $this->color ?? Color::SUCCESS();
    }

    public function status(): ?Color
    {
        return $this->status ?? Color::INFO();
    }
};
