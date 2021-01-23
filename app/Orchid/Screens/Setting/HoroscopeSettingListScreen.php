<?php

namespace App\Orchid\Screens\Setting;

use App\Models\HoroscopeSettingModel;
use App\Orchid\Layouts\Setting\HoroscopeSettingListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class HoroscopeSettingListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройки гороскопа';

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
            'horoscopeSettings' => HoroscopeSettingModel::paginate(),
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
                ->route('platform.setting.horoscope.edit')
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
            HoroscopeSettingListLayout::class
        ];
    }
}
