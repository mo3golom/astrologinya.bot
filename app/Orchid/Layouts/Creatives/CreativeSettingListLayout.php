<?php

namespace App\Orchid\Layouts\Creatives;

use App\Models\CreativeSettingModel;
use App\Models\HoroscopeModel;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CreativeSettingListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'creativeSettings';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('creative_setting_id', 'ID'),
            TD::make('name', 'Название'),
            TD::make('is_complete', 'Завершен')
                ->render(static function (CreativeSettingModel $model) {
                    return $model->is_complete ? 'Да' : 'Нет';
                })
            ,
            TD::make('last_generated_at', 'Последняя генерация'),
            TD::make('created_at', 'Создано'),
            TD::make('actions', 'Действия')
                ->render(static function (CreativeSettingModel $model) {
                    return
                        Link::make()
                            ->icon('pencil')
                            ->route('platform.service.creative.settings.edit', $model)
                        ;
                })
            ,
        ];
    }
}
