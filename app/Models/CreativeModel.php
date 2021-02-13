<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property-read int $creative_id
 * @property int $creative_setting_id
 * @property string $object_name
 * @property int $object_id
 * @property int $attachment_id
 * @property-read CreativeSettingModel $creativeSetting
 * @property-read Attachment $attachment
 *
 * Class CreativeModel
 */
class CreativeModel extends Model
{
    use AsSource;
    use Filterable;
    use HasFactory;

    protected $table = 'creatives';

    protected $primaryKey = 'creative_id';

    protected $fillable = [
        'creative_setting_id',
        'object_name',
        'object_id',
        'attachment_id',
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'creative_setting_id',
        'object_name',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'creative_setting_id',
        'object_name',
    ];

    public function creativeSetting(): HasOne
    {
        return $this->hasOne(CreativeSettingModel::class, 'creative_setting_id', 'creative_setting_id');
    }

    public function attachment(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'attachment_id')->withDefault();
    }
}
