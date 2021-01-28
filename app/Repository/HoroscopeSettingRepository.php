<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\HoroscopeSettingModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class HoroscopeSettingRepository
 */
class HoroscopeSettingRepository extends ModelRepository
{
    /**
     * @var HoroscopeSettingModel
     */
    protected $model;

    /**
     * HoroscopeSettingRepository constructor.
     *
     * @param HoroscopeSettingModel $model
     */
    public function __construct(HoroscopeSettingModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getAllWithoutActualHoroscope(): Collection
    {
        return
            $this->model
                ->newQuery()
                ->select(['horoscope_setting.*'])
                ->leftJoin('horoscope as h', 'h.horoscope_setting_id', '=', 'horoscope_setting.horoscope_setting_id')
                ->whereNull('h.horoscope_id')
                ->orWhere(DB::raw("date_part('day', h.created_at)"), '<', Carbon::now()->day)
                ->get()
            ;
    }
}