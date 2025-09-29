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
        'city_name','city_code',
        'barangay_name','barangay_code',
        'street',
        'status',
    ];

    public function getFullAddressAttribute()
    {
        $regions = collect(json_decode(file_get_contents(public_path('json/region.json')), true));
        $provinces = collect(json_decode(file_get_contents(public_path('json/province.json')), true));
        $cities = collect(json_decode(file_get_contents(public_path('json/city.json')), true));
        $barangays = collect(json_decode(file_get_contents(public_path('json/barangay.json')), true));

        $barangay = $barangays->firstWhere('brgy_code', $this->barangay_code)['brgy_name'] ?? null;
        $city = $cities->firstWhere('city_code', $this->city_code)['city_name'] ?? null;
        $province = $provinces->firstWhere('province_code', $this->province_code)['province_name'] ?? null;
        $region = $regions->firstWhere('region_code', $this->region_code)['region_name'] ?? null;

        return collect([$barangay, $city, $province, $region])
            ->filter()
            ->join(', ');
    }
      
}
