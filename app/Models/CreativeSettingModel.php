<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class CreativeSettingModel extends Model
{
    use AsSource;
    use HasFactory;

    protected $table = 'creative_settings';

    protected $primaryKey = 'creative_setting_id';

    protected $fillable = [
        'is_active',
        'name',
        'type',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];
}
