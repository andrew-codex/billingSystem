<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineMan extends Model
{
    protected $table = 'linemen';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'contact_number',
        'availability',
        'region_code',
        'province_code',
        'city_code',
        'barangay_code',
        'street',
        'status',
    ];
}
