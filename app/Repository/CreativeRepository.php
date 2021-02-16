<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\CreativeModel;
use Illuminate\Database\Eloquent\Collection;

class CreativeRepository extends ModelRepository
{
    /**
     * @var CreativeModel
     */
    protected $model;

    /**
     * CreativeRepository constructor.
     *
     * @param CreativeModel $model
     */
    public function __construct(CreativeModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $objectName
     * @param array $objectIds
     * @return Collection
     */
    public function getByObjectNameAndObjectIds(string $objectName, array $objectIds): Collection
    {
        return
            $this->model
                ->newQuery()
                ->where('object_name', '=', $objectName)
                ->whereIn('object_id', $objectIds)
                ->get()
            ;
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteByIds(array $ids)
    {
        return
            $this->model
                ->newQuery()
                ->whereIn('creative_id', $ids)
                ->delete()
            ;
    }
}