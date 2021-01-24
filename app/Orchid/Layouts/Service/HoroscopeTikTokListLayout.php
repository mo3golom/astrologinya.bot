<?php

namespace App\Orchid\Layouts\Service;

use App\Models\HoroscopeModel;
use App\Models\HoroscopeSettingModel;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class HoroscopeTikTokListLayout extends Table
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
                ->render(static function (HoroscopeModel $model) {
                    return $model->setting->zodiac ?? '';
                })
            ,
            TD::make('short_description', 'Описание')
                ->render(function (HoroscopeModel $model) {
                    return view('orchid.layout.td_copy_text', [
                        'value' => $model->short_description ?? '',
                    ]);
                })
            ,
        ];
    }
}
