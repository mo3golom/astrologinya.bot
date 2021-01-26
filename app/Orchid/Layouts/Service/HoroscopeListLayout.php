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
                ->render(static function (HoroscopeModel $model) {
                    return Link::make($model->setting->zodiac ?? '')
                        ->route('platform.service.horoscope.edit', $model)
                        ;
                })
            ,
            TD::make('description', 'Описание')
                ->render(static function (HoroscopeModel $model) {
                    return mb_strimwidth($model->description, 0, 50, "...");
                })
            ,
            TD::make('video_id', 'Видео')
                ->align(TD::ALIGN_CENTER)
                ->render(function (HoroscopeModel $model) {
                    return
                        null !== $model->attachment ?
                            Link::make('Скачать')
                                ->href($model->attachment->url())
                                ->target('_blank')
                            : '';
                })
            ,
            TD::make('is_send', 'Статус')
                ->render(static function (HoroscopeModel $model) {
                    return $model->is_send ?
                        sprintf('<span class="badge bg-success text-white">Отправлено в %s</span>', $model->send_at->format('H:i'))
                        : sprintf('<span class="badge bg-info text-white">Ожидает отправки в %s</span>', $model->setting->send_time->format('H:i'));
                }),
            TD::make('created_at', 'Создано'),
        ];
    }
}
