<?php

declare(strict_types=1);

namespace App\Service\Creatives\Fields;

use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class OnePageCurveTextAnimationVideoFields implements CreativeFieldsInterface
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
                    Input::make('duration')
                        ->type('number')
                        ->value(15)
                        ->title('Максимальная продолжительность видео')
                        ->required()
                    ,
                    Input::make('frame_duration')
                        ->type('number')
                        ->value(1)
                        ->title('Продолжительность кадра')
                        ->required()
                    ,
                    Input::make('text_prefix')
                        ->value('• ')
                        ->title('Префикс для перечисления (пробел учитывается)')
                        ->required()
                    ,
                ]
            ))
                ->title('Настройки для генератора')
            ;
    }
}