<?php

namespace Orchid\Crud\Screens;

use Illuminate\Support\Arr;
use Orchid\Crud\CrudCard;
use Orchid\Crud\CrudScreen;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Card;


use Orchid\Screen\Layouts\Compendium;
use Orchid\Support\Facades\Layout;

class ViewScreen extends CrudScreen
{
    /**
     * Query data.
     *
     * @param ResourceRequest $request
     *
     * @return array
     */
    public function query(ResourceRequest $request): array
    {
        $cardCompendium = new CrudCard();
        $cardText = new CrudCard();

        $model = Arr::dot($request->findModelOrFail()->toArray());
        $cardCompendium->title = $this->resource->label();
        $cardDescription = [];

        foreach ($this->resource->fields() as $field) {
            if ($field instanceof Upload) {
                continue;
            }
            if ($field instanceof Picture) {
                continue;
            }
            if ($field instanceof Quill) {
                $cardText->title = $field->get('title');
                $cardText->description = $model[$field->get('name')];

                continue;
            }
            $cardDescription[$field->get('title')] = $model[$field->get('name')];
        };
        $cardCompendium->description = new Compendium($cardDescription);

        return [
            'cardCompendium' => $cardCompendium,
            'cardText' => $cardText,
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            new Card('cardCompendium'),
            new Card('cardText'),
        ];
    }
}
