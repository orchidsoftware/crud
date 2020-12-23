<?php

namespace Orchid\Crud\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;

class DefaultSort extends Filter
{
    /**
     * @var array
     */
    public $parameters = [];

    /**
     * @var bool
     */
    public $display = false;

    /**
     * @var string|null
     */
    protected $sortColumn;

    /**
     * @var string
     */
    protected $sortOrder;

    /**
     * Filter constructor.
     *
     * @param string      $sortOrder
     * @param string|null $sortColumn
     */
    public function __construct(string $sortOrder = 'asc', string $sortColumn = null)
    {
        parent::__construct();

        $this->sortOrder = $sortOrder;
        $this->sortColumn = $sortColumn;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return '';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->defaultSort(
            $this->sortColumn ?? $builder->getModel()->getKeyName(),
            $this->sortOrder
        );
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [];
    }
}
