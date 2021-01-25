<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;

/**
 * @property-read int $horoscope_id
 * @property-read HoroscopeSettingModel $setting
 * @property int $horoscope_setting_id
 * @property string|null $short_description
 * @property string $description
 * @property boolean $is_send
 * @property Carbon $send_at
 * @property int $video_id
 * @property-read Attachment $attachment
 * @property-read string $render_template
 *
 * Class HoroscopeModel
 */
class HoroscopeModel extends Model
{
    use AsSource;
    use HasFactory;

    protected $table = 'horoscope';

    protected $primaryKey = 'horoscope_id';

    protected $dates = ['send_at'];

    protected $fillable = [
        'horoscope_setting_id',
        'short_description',
        'description',
        'is_send',
        'send_at',
        'video_id',
    ];

    public function setting(): HasOne
    {
        return $this->hasOne(HoroscopeSettingModel::class, 'horoscope_setting_id', 'horoscope_setting_id');
    }

    public function attachment(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'video_id')->withDefault();
    }

    public function getRenderTemplateAttribute(): string
    {
        return str_replace(
            [
                '{{date}}',
                '{{description}}',
                '{{zodiac}}',
            ],
            [
                Carbon::now()->format('d.m.Y'),
                $this->description ?? '',
                $this->setting->zodiac ?? '',
            ],
            $this->setting->template
        );
    }
}
