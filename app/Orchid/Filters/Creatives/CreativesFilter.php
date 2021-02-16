<?php

namespace App\Orchid\Filters\Creatives;

use App\Models\CreativeSettingModel;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class CreativesFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = ['creative_setting_id', 'object_name'];

    /**
     * @return string
     */
    public function name(): string
    {
        return '';
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return
            $builder
                ->where('creative_setting_id', $this->request->get('creative_setting_id'))
            ;
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('creative_setting_id')
                ->fromModel(CreativeSettingModel::class, 'name', 'creative_setting_id')
                ->empty()
                ->value($this->request->get('creative_setting_id'))
                ->title('Тип креатива'),
        ];
    }
}
