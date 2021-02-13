<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\CreativeModel;

class CreativeRepository extends ModelRepository
{
    /**
     * @var CreativeModel
     */
    protected $model;

    public function __construct(CreativeModel $model)
    {
        $this->model = $model;
    }
}