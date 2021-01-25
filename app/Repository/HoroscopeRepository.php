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
        $query = $this->model
            ->newQuery()
            ->where('horoscope_setting_id', '=', $horoscopeSettingId)
        ;

        // Удаляем вложения
        $horoscopes = $query->with('attachment')->get();
        $horoscopes->map(static function (HoroscopeModel $horoscopeModel) {
            $horoscopeModel->attachment->delete();
        });

        return $query->delete();
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

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getFirstWithoutVideo()
    {
        return
            $this->model
                ->newQuery()
                ->whereNull('video_id')
                ->first()
            ;
    }
}