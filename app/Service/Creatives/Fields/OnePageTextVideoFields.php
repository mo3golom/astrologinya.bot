<?php

declare(strict_types=1);

namespace App\Service\Creatives\Fields;

use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class OnePageTextVideoFields implements CreativeFieldsInterface
{

    /**
     * @var BaseTextVideoFields
     */
    private $baseTextVideoFields;

    public function __construct(BaseTextVideoFields $baseTextVideoFields)
    {
        $this->baseTextVideoFields = $baseTextVideoFields;
    }

    /**
     * @return Rows
     */
    public function getFields(): Rows
    {
        return
            Layout::rows(array_merge(
                $this->baseTextVideoFields->getFieldsAr(),
                [
                    Input::make('line_max_length')
                        ->type('number')
                        ->value(45)
                        ->title('Максимальная длина одной строки')
                        ->required()
                    ,
                ]
            ))
                ->title('Настройки для генератора')
            ;
    }
}