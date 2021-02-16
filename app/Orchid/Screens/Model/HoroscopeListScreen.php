<?php

namespace App\Orchid\Screens\Model;

use App\Models\HoroscopeModel;
use App\Orchid\Layouts\Model\HoroscopeListLayout;
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

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'horoscopes' => HoroscopeModel::orderBy('horoscope_id', 'asc')->paginate(),
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
            HoroscopeListLayout::class,
        ];
    }
}
