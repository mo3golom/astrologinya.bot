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
     * @return Rows
     */
    public function getFields(): Rows
    {
        return Layout::rows([
            Input::make('box_width')
                ->type('number')
                ->value(1080)
                ->title('Ширина бокса')
                ->help('Ширина картинки, в которую будет помещен текст')
                ->required()
            ,
            Input::make('box_height')
                ->type('number')
                ->value(640)
                ->title('Высота бокса')
                ->help('Высота картинки, в которую будет помещен текст')
                ->required()
            ,
            Input::make('text_offset')
                ->type('number')
                ->value(0)
                ->title('Отступ от краев бокса')
                ->required()
            ,
            Input::make('font_size')
                ->type('number')
                ->value(60)
                ->title('Размер шрифта')
                ->required()
            ,
            Input::make('text_color')
                ->value('#ffffff')
                ->title('Цвет текста')
                ->required()
            ,
            Input::make('line_max_length')
                ->type('number')
                ->value(45)
                ->title('Максимальная длина одной строки')
                ->required()
            ,
            Input::make('position_x')
                ->type('number')
                ->value(0)
                ->title('Расположение текста по X')
                ->required()
            ,
            Input::make('position_y')
                ->type('number')
                ->value(0)
                ->title('Расположение текста по Y')
                ->required()
            ,
        ])->title('Настройки для генератора');
    }
}