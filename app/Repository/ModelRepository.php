<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ModelRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public function find(int $id)
    {
        $key = $this->model->getKeyName();

        return
            $this->model
                ->newQuery()
                ->where($key, '=', $id)
                ->first()
            ;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function findOrNew(int $id)
    {
        $key = $this->model->getKey();

        return
            $this->model
                ->newQuery()
                ->where($key, '=', $id)
                ->firstOrNew()
            ;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return
            $this->model
                ->newQuery()
                ->get()
            ;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return
            $this->model
                ->newQuery()
                ->create($data)
            ;
    }

    /**
     * @param $model
     * @param array $data
     * @return mixed
     */
    public function update($model, array $data)
    {
        return $model->fill($data)->save();
    }
}