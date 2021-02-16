<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * @property-read int $creative_setting_id
 * @property boolean $is_active
 * @property boolean $is_complete
 * @property string $name
 * @property string $object_getter_class
 * @property string $generator_class
 * @property array $settings
 * @property Carbon $last_generated_at
 *
 * Class CreativeSettingModel
 */
class CreativeSettingModel extends Model
{
    use AsSource;
    use HasFactory;

    protected $table = 'creative_settings';

    protected $primaryKey = 'creative_setting_id';

    protected $fillable = [
        'is_active',
        'name',
        'object_getter_class',
        'generator_class',
        'settings',
        'is_complete',
        'last_generated_at',

    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_complete' => 'boolean',
        'settings' => 'array',
        'created_at' => 'datetime:d.m.Y H:i:s',
        'last_generated_at' => 'datetime:d.m.Y H:i:s',
    ];

    protected $dates = ['last_generated_at'];
}
