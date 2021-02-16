<?php

namespace App\Orchid\Layouts\Model;

use App\Models\HoroscopeModel;
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
            TD::make('horoscope_id', 'ID'),
            TD::make('zodiac_name', 'Знак зодиака')
            ,
            TD::make('description', 'Описание')
                ->render(static function (HoroscopeModel $model) {
                    return mb_strimwidth($model->description, 0, 50, "...");
                })
            ,
            TD::make('video_url', 'Видео')
                ->align(TD::ALIGN_CENTER)
                ->render(function (HoroscopeModel $model) {
                    return (
                        null !== $model->video->id
                        && null !== $model->video->url()
                    )
                        ? Link::make($model->video->name)
                            ->href($model->video->url())
                            ->target('_blank')
                        : '';
                })
            ,
            TD::make('created_at', 'Создано'),
            TD::make('actions', 'Действия')
                ->render(static function (HoroscopeModel $model) {
                    return Link::make()
                        ->icon('pencil')
                        ->route('platform.service.horoscope.edit', $model)
                        ;
                })
        ];
    }
}
