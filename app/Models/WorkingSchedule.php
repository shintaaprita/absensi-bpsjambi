<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingSchedule extends Model
{
    protected $fillable = [
        'satker_code',
        'day_name',
        'clock_in',
        'clock_out',
        'overtime_limit',
        'is_working_day',
    ];
}
