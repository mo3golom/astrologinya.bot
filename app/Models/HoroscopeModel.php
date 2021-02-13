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
 * @property string $zodiac_name
 * @property string $description_parse_url
 * @property string $description
 * @property integer|null $video_id
 * @property Attachment|null $video
 *
 * Class HoroscopeModel
 */
class HoroscopeModel extends Model
{
    use AsSource;
    use HasFactory;

    protected $table = 'horoscope';

    protected $primaryKey = 'horoscope_id';

    protected $fillable = [
        'zodiac_name',
        'description_parse_url',
        'description',
        'video_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    public function video(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'video_id')->withDefault();
    }

    public static function getZodiacEnum(): array
    {
        return config('enums.zodiac', []);
    }
}
