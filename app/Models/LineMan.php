<?php

namespace App\Models;
use Schoolees\Psgc\Models\Region;
use Schoolees\Psgc\Models\Province;
use Schoolees\Psgc\Models\City;
use Schoolees\Psgc\Models\Barangay;
use Illuminate\Database\Eloquent\Model;

class LineMan extends Model
{
      protected $appends = ['full_address'];
    protected $table = 'linemen';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'contact_number',
        'availability',
        'region_name', 'region_code',
        'province_name','provine_code',
        'city_name','city_code',
        'barangay_name','barangay_code',
        'street',
        'status',
    ];

    public function getFullAddressAttribute()
    {
        $barangay = $this->barangay?->name;
        $city = $this->city?->name;
        $province = $this->province?->name;
        $region = $this->region?->name;

        return collect([$barangay, $city, $province, $region])
            ->filter()
            ->join(', ');
    }

      
}
