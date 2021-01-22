<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\HoroscopeModel;

/**
 * Class HoroscopeRepository
 */
class HoroscopeRepository extends ModelRepository
{
    /**
     * @var HoroscopeModel
     */
    protected $model;

    public function __construct(HoroscopeModel $model)
    {
        $this->model = $model;
    }
}