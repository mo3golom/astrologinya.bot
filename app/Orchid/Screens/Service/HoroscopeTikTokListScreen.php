<?php

namespace App\Orchid\Screens\Service;

use App\Models\HoroscopeModel;
use App\Orchid\Layouts\Service\HoroscopeListLayout;
use App\Orchid\Layouts\Service\HoroscopeTikTokListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class HoroscopeTikTokListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Гороскопы для Тиктока';

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
            'horoscopes' => HoroscopeModel::paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            HoroscopeTikTokListLayout::class,
        ];
    }
}
