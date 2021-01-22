<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\HoroscopeSettingModel;

/**
 * Class HoroscopeSettingRepository
 */
class HoroscopeSettingRepository extends ModelRepository
{
    /**
     * @var HoroscopeSettingModel
     */
    protected $model;

    public function __construct(HoroscopeSettingModel $model)
    {
        $this->model = $model;
    }
}