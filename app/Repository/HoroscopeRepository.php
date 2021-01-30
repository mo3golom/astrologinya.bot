<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\HoroscopeModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return Collection
     */
    public function getAllNoSend(): Collection
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
     * @return Model|null
     */
    public function getFirstWithoutVideo(): ?Model
    {
        return
            $this->model
                ->newQuery()
                ->whereNull('video_url')
                ->first()
            ;
    }

    /**
     * @return Collection
     */
    public function getAllWithVideo(): Collection
    {
        return
            $this->model
                ->newQuery()
                ->whereNotNull('video_url')
                ->get()
            ;
    }
}