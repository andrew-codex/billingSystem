<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class ReconnectionController extends Controller
{
        public function index(){


        $availableCount = LineMan::where('availability', 1)->count();

        $linemen = LineMan::orderBy('created_at', 'desc')->get();

        
        $regions = json_decode(file_get_contents(public_path('json/region.json')), true);
        $provinces = json_decode(file_get_contents(public_path('json/province.json')), true);
        $cities = json_decode(file_get_contents(public_path('json/city.json')), true);
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')), true);

   $linemen->transform(function($lineman) use ($regions, $provinces, $cities, $barangays) {
    $region = collect($regions)->firstWhere('region_code', $lineman->region_code);
    $lineman->region_name = $region['region_name'] ?? $lineman->region_name ?? '';

    $province = collect($provinces)->firstWhere('province_code', $lineman->province_code);
    $lineman->province_name = $province['province_name'] ?? $lineman->province_name ?? '';

    $city = collect($cities)->firstWhere('city_code', $lineman->city_code);
    $lineman->city_name = $city['city_name'] ?? $lineman->city_name ?? '';

    $barangay = collect($barangays)->firstWhere('brgy_code', $lineman->barangay_code);
    $lineman->barangay_name = $barangay['brgy_name'] ?? $lineman->barangay_name ?? '';

    return $lineman;
});

        
            return view('pages.reconnection', compact( 'linemen', 'availableCount'));
        }
}
