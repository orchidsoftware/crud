<?php

namespace Orchid\Crud\Layouts;

use Orchid\Screen\Builder;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Repository;

class ResourceFields extends Rows
{
    public const PREFIX = 'model';

    /**
     * @var Field[]
     */
    private $fields;

    /**
     * ResourceFields constructor.
     *
     * @param Field[] $fields
     */
    public function __construct(array $fields)
    {
       $this->fields = $fields;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return $this->fields;
    }

    /**
     * @param Repository $repository
     *
     *
     * @return \Illuminate\View\View
     * @throws \Throwable
     */
    public function build(Repository $repository)
    {
        $form = new Builder($this->fields(), $repository);

        return view($this->template, [
            'form'  => $form->setPrefix(self::PREFIX)->generateForm(),
            'title' => $this->title,
        ]);
    }
}

