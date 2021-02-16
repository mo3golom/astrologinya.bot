<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\HoroscopeModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * @return Collection
     */
    public function getAllNoSend(): Collection
    {
        return
            $this->model
                ->newQuery()
                ->with(['setting'])
                ->WhereNull('message_id')
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
                ->whereNull('video_id')
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
                ->whereNotNull('video_id')
                ->orderBy('horoscope_setting_id', 'asc')
                ->get()
            ;
    }

    /**
     * @return Collection
     */
    public function getAllWithoutActualHoroscope(): Collection
    {
        return
            $this->model
                ->newQuery()
                ->whereNull('description')
                ->orWhere(DB::raw("date_part('day', updated_at)"), '!=', Carbon::now()->day)
                ->get()
            ;
    }
}