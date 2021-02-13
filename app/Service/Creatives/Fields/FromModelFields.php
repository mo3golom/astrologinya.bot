<?php

declare(strict_types=1);

namespace App\Service\Creatives\Fields;

use App\Models\HoroscopeModel;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;
use ReflectionClass;

class FromModelFields implements CreativeFieldsInterface
{
    private const MODELS_CLASSES_LIST = [
        HoroscopeModel::class,
    ];

    /**
     * @return Rows
     * @throws \ReflectionException
     */
    public function getFields(): Rows
    {
        $classes = [];
        foreach (self::MODELS_CLASSES_LIST as $modelClass) {
            $reflect = new ReflectionClass($modelClass);
            $classes[$modelClass] = $reflect->getShortName();
        }

        return Layout::rows([
            Select::make('model_class')
                ->options($classes)
                ->title('Модель')
                ->help('Модель, из которой будут браться данные')
                ->empty()
                ->required()
            ,
            Input::make('title_key')
                ->title('Поле заголовка')
                ->value('title')
                ->help('Поле в модели для заголовка (если заголовок используется в генераторе)')
            ,
            Input::make('text_key')
                ->title('Поле текста')
                ->value('text')
                ->help('Поле в модели для текста')
                ->required()
            ,
            Input::make('attachment_id_key')
                ->title('Поле ID вложения')
                ->value('attachment_id')
                ->help('Поле в модели c ID вложения')
                ->required()
            ,
        ])->title('Настройки для геттера');
    }

}