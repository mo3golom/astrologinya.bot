<?php

namespace App\Orchid\Screens\Creatives;

use App\Models\CreativeModel;
use App\Orchid\Layouts\Creatives\CreativesFiltersLayout;
use App\Orchid\Layouts\Creatives\CreativesListLayout;
use Orchid\Screen\Screen;

class CreativesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список сгенерированных креативов';

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
            'creatives' => CreativeModel::filters()
                ->filtersApplySelection(CreativesFiltersLayout::class)
                ->defaultSort('creative_id')
                ->paginate(),
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
            CreativesFiltersLayout::class,
            CreativesListLayout::class,
        ];
    }
}
