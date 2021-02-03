<?php

namespace App\Orchid\Screens\Service;

use App\Models\HoroscopeModel;
use App\Orchid\Layouts\Service\HoroscopeListLayout;
use App\Repository\HoroscopeRepository;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class HoroscopeListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Гороскопы';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    public function __construct(HoroscopeRepository $horoscopeRepository)
    {
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'horoscopes' => HoroscopeModel::orderBy('horoscope_setting_id', 'asc')->paginate(),
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
                ->route('platform.service.horoscope.edit'),
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
            Layout::rows([
                Link::make('Api для получения ссылок на видео')
                    ->route('horoscope.api.urls')
                ,
            ]),
            HoroscopeListLayout::class,
        ];
    }
}
