<?php

namespace App\Orchid\Layouts\Creatives;

use App\Orchid\Filters\Creatives\CreativesFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class CreativesFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            CreativesFilter::class,
        ];
    }
}
