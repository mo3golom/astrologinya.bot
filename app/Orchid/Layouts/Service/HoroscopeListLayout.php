<?php

namespace App\Orchid\Layouts\Service;

use App\Models\HoroscopeModel;
use App\Models\HoroscopeSettingModel;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class HoroscopeListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'horoscopes';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('horoscope_id', 'Знак зодиака')
                ->render(function (HoroscopeModel $model) {
                    return Link::make($model->horoscopeSetting->zodiac)
                        ->route('platform.service.horoscope.edit', $model)
                        ;
                })
            ,
            TD::make('description', 'Описание'),
            TD::make('short_description', 'Короткое описание'),
            TD::make('created_at', 'Создано'),
        ];
    }
}
