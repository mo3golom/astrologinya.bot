<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $horoscope_id
 * @property HoroscopeSettingModel $horoscopeSetting
 * @property string|null $short_description
 * @property string $description
 *
 * Class HoroscopeModel
 */
class HoroscopeModel extends Model
{
    use HasFactory;

    protected $table = 'horoscope';

    protected $primaryKey = 'horoscope_id';

    protected $fillable = [
        'horoscope_setting_id',
        'short_description',
        'description',
    ];

    public function horoscopeSetting(): HasOne
    {
        return $this->hasOne(HoroscopeSettingModel::class, 'horoscope_setting_id', 'horoscope_setting_id');
    }
}
