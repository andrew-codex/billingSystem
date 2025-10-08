<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ElectricMeter;  
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Consumer extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

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

     protected $hidden = ['password'];

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
