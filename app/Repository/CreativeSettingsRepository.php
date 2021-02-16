<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\CreativeSettingModel;

class CreativeSettingsRepository extends ModelRepository
{
    protected $model;

    /**
     * CreativeSettingsRepository constructor.
     *
     * @param CreativeSettingModel $model
     */
    public function __construct(CreativeSettingModel $model)
    {
        $this->model = $model;
    }

    public function getFirstNotCompleteActiveSetting()
    {
        return
            $this->model
                ->newQuery()
                ->where('is_active', '=', true)
                ->where('is_complete', '=', false)
                ->orderBy('last_generated_at')
                ->first()
            ;
    }

    /**
     * @param array $ids
     * @return int
     */
    public function setNotCompleteByIds(array $ids): int
    {
        return
            $this->model
                ->newQuery()
                ->whereIn('creative_setting_id', $ids)
                ->update(['is_complete' => false])
            ;
    }
}