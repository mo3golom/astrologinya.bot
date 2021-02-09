<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativeModel extends Model
{
    use HasFactory;

    protected $table = 'creatives';

    protected $primaryKey = 'creative_id';

    protected $fillable = [
        'creative_setting_id',
        'object_name',
        'object_id',
        'attachment_id',
    ];
}
