<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\HoroscopeSettingModel;
use Carbon\Carbon;
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
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getWithoutActualHoroscope()
    {
        return
            $this->model
                ->newQuery()
                ->select(['horoscope_setting.*'])
                ->leftJoin('horoscope as h', 'h.horoscope_setting_id', '=', 'horoscope_setting.horoscope_setting_id')
                ->whereNull('h.horoscope_id')
                ->orWhere(DB::raw('DAY(h.created_at)'), '<', Carbon::now()->day)
                ->first()
            ;
    }

    public function getWithoutActualHoroscopeVideo()
    {
        return
            $this->model
                ->newQuery()
                ->select(['horoscope_setting.*'])
                ->leftJoin('horoscope as h', 'h.horoscope_setting_id', '=', 'horoscope_setting.horoscope_setting_id')
                ->whereNotNull('h.horoscope_id')
                ->first()
            ;
    }
}