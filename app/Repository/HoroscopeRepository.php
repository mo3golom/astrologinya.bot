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

    /**
     * HoroscopeRepository constructor.
     *
     * @param HoroscopeModel $model
     */
    public function __construct(HoroscopeModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $horoscopeSettingId
     * @return mixed
     */
    public function deleteByHoroscopeSettingId(int $horoscopeSettingId)
    {
        return
            $this->model
                ->newQuery()
                ->where('horoscope_setting_id', '=', $horoscopeSettingId)
                ->delete()
            ;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllNoSend()
    {
        return
            $this->model
                ->newQuery()
                ->with(['setting'])
                ->where('is_send', '=', false)
                ->orWhereNull('is_send')
                ->get()
            ;
    }
}