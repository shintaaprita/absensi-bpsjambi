<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatkerSetting extends Model
{
    protected $fillable = [
        'satker_code',
        'key',
        'value',
        'description',
    ];
}
