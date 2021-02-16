<?php

namespace App\Orchid\Layouts\Creatives;

use App\Models\CreativeModel;
use App\Orchid\Filters\Creatives\CreativesFilter;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CreativesListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'creatives';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('creative_id', 'ID'),
            TD::make('creative_setting_id', 'Тип креатива')
                ->render(static function (CreativeModel $model) {
                    return
                        Link::make($model->creativeSetting->name)
                            ->route('platform.service.creative.settings.edit', $model->creativeSetting)
                            ->target('_blank')
                        ;
                })
            ,
            TD::make('attachment_id', 'Креатив')
                ->align(TD::ALIGN_CENTER)
                ->render(function (CreativeModel $model) {
                    return (
                        null !== $model->attachment->id
                        && null !== $model->attachment->url()
                    )
                        ? Link::make($model->attachment->name)
                            ->href($model->attachment->url())
                            ->target('_blank')
                        : '';
                })
            ,
        ];
    }
}
