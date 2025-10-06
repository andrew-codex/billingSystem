<?php

namespace App\Models;
use Schoolees\Psgc\Models\Region;
use Schoolees\Psgc\Models\Province;
use Schoolees\Psgc\Models\City;
use Schoolees\Psgc\Models\Barangay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ElectricMeter;  
 
class Consumer extends Model
{
    use HasFactory;

    protected $appends = ['full_name', 'full_address'];
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable =[
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'email',
        'password',
        'city_name','city_code',
        'barangay_name','barangay_code',
        'street',
        'phone',
        'status',
        'must_change_password',
    ];

    public function electricMeters()
{
    return $this->hasMany(ElectricMeter::class);
}

public function receivedMeters()
{
    return $this->hasMany(ElectricMeter::class, 'consumer_id');
}


    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}");
    }

   
 

       
}
