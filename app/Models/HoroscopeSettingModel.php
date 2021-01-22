<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $horoscope_setting_id
 * @property string $zodiac
 * @property string $parse_url
 * @property string $short_description_parse_url
 * @property string $template
 * @property Carbon $send_time
 *
 * Class HoroscopeSettingModel
 */
class HoroscopeSettingModel extends Model
{
    use HasFactory;

    protected $table = 'horoscope_setting';

    protected $primaryKey = 'horoscope_setting_id';

    protected $dates = ['send_time'];

    protected $fillable = [
        'zodiac',
        'parse_url',
        'short_description_parse_url',
        'template',
        'send_time',
    ];

    public function getZodiacEnum(): array
    {
        return config('enums.zodiac', []);
    }
}
