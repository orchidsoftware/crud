<?php

namespace Orchid\Crud\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;

class DefaultSorted extends Filter
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
    public function __construct(string $sortColumn = null, string $sortOrder = 'asc')
    {
        parent::__construct();

        $this->sortColumn = $sortColumn;
        $this->sortOrder = $sortOrder;
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
