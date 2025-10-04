<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrownOutSchedule extends Model
{
    protected $table = 'brownout_schedules';
    protected $fillable = [
        'area',
        'schedule_date',
        'start_time',
        'end_time',
        'description',
        'status'

    ];
}
