<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;

/**
 * @property-read int $horoscope_setting_id
 * @property string $zodiac
 * @property string $parse_url
 * @property string $short_description_parse_url
 * @property string $template
 * @property Carbon $send_time
 * @property int $template_video_id
 * @property-read int $template_video_url
 * @property-read Attachment $templateVideo
 *
 * Class HoroscopeSettingModel
 */
class HoroscopeSettingModel extends Model
{
    use AsSource;
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
        'template_video_id',
    ];

    protected $casts = [
        'send_time' => 'datetime:H:i',
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    public static function getZodiacEnum(): array
    {
        return config('enums.zodiac', []);
    }

    public function getTemplateVideoUrlAttribute(): string
    {
        return $this->templateVideo->url() ?? '';
    }

    public function templateVideo(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'template_video_id')->withDefault();
    }
}
