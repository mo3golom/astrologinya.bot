<?php

namespace App\Orchid\Layouts\Setting;

use App\Models\HoroscopeSettingModel;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class HoroscopeSettingListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'horoscopeSettings';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('horoscope_setting_id', 'ID'),
            TD::make('title', 'Знак зодиака')
                ->render(function (HoroscopeSettingModel $model) {
                    return Link::make($model->zodiac)
                        ->route('platform.setting.horoscope.edit', $model)
                        ;
                })
            ,
            TD::make('template_video_id', 'Шаблон видео')
                ->align(TD::ALIGN_CENTER)
                ->render(function (HoroscopeSettingModel $model) {
                    return (
                        null !== $model->templateVideo->id
                        && null !== $model->templateVideo->url()
                    )
                        ? Link::make($model->templateVideo->original_name)
                            ->href($model->templateVideo->url())
                            ->target('_blank')
                        : '';
                })
            ,
            TD::make('send_time', 'Время отправки сообщения'),
            TD::make('created_at', 'Создано'),
        ];
    }
}
