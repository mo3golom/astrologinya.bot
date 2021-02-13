<?php

namespace App\Orchid\Screens\Creatives;

use App\Models\CreativeSettingModel;
use App\Orchid\Layouts\Creatives\CreativeSettingListLayout;
use App\Orchid\Layouts\Model\HoroscopeListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CreativeSettingListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список настроек креативов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'creativeSettings' => CreativeSettingModel::paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Создать')
                ->icon('pencil')
                ->route('platform.service.creative.settings.edit'),
        ];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            CreativeSettingListLayout::class,
        ];
    }
}
