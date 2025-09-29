<?php

namespace App\Http\Controllers;

use App\Models\LineMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LineManController extends Controller
{


    public function createLineMan(Request $request){
        $request->validate([
             'first_name' => 'required',
             'last_name' => 'required',
             'middle_name' => 'nullable',
             'suffix' => 'nullable',
             'city_code' => 'nullable|string|max:255',
            'city_name' => 'nullable|string|max:255',
               'barangay_code' => 'nullable|string|max:255',
            'barangay_name' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
             'availability' => 'nullable',
             'contact_number' => 'required',

             
        ]);
        



        Lineman::Create([
           'first_name' =>$request->first_name,
           'last_name' =>$request->last_name,
           'middle_name' =>$request->middle_name,
           'suffix' => $request->suffix,
          
         'city_code' => $request->city_code,
        'city_name' => $request->city_name,
         'barangay_code' => $request->barangay_code,
        'barangay_name' => $request->barangay_name,
        'street' => $request->street,
           'contact_number' => $request->contact_number,
           'status' => 'active',

        ]);

        return redirect()->route('reconnection.index')->with('success', 'Line Man added succesfully. ');
    }

    public function updateLineMan(Request $request, $id)
{
    $lineman = LineMan::findOrFail($id);

    $request->validate([
        'first_name' => 'required|string|max:50',
        'last_name' => 'required|string|max:50',
        'suffix' => 'nullable|string|max:10',
        'contact_number' => 'nullable|string|max:20',
       
             'city_code' => 'nullable|string|max:255',
            'city_name' => 'nullable|string|max:255',
               'barangay_code' => 'nullable|string|max:255',
            'barangay_name' => 'nullable|string|max:255',
        'street' => 'nullable|string|max:255',
       
    ]);

    
    $regions = json_decode(file_get_contents(public_path('json/region.json')), true);
    $provinces = json_decode(file_get_contents(public_path('json/province.json')), true);
    $cities = json_decode(file_get_contents(public_path('json/city.json')), true);
    $barangays = json_decode(file_get_contents(public_path('json/barangay.json')), true);

   
    $region_code = $request->region_code;
    $region_name = collect($regions)->firstWhere('region_code', $region_code)['region_name'] ?? null;

    $province_code = $request->province_code;
    $province_name = collect($provinces)->firstWhere('province_code', $province_code)['province_name'] ?? null;

    $city_code = $request->city_code;
    $city_name = collect($cities)->firstWhere('city_code', $city_code)['city_name'] ?? null;

    $barangay_code = $request->barangay_code;
    $barangay_name = collect($barangays)->firstWhere('brgy_code', $barangay_code)['brgy_name'] ?? null;

    $lineman->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'suffix' => $request->suffix,
        'contact_number' => $request->contact,
         'city_code' => $request->city_code,
        'city_name' => $request->city_name,
         'barangay_code' => $request->barangay_code,
        'barangay_name' => $request->barangay_name,
        'street' => $request->street,
       
    ]);

    return redirect()->back()->with('success', 'Line man updated successfully!');
}


    public function deactivate(LineMan $lineman) {
        $lineman->status = 'inactive';
        $lineman->save();
        return back()->with('success', 'Lineman deactivated.');
    }

    public function activate(LineMan $lineman) {
        $lineman->status = 'active';
        $lineman->save();
        return back()->with('success', 'Lineman activated.');
    }

    public function onLeave(LineMan $lineman) {
        $lineman->status = 'on_leave';
        $lineman->save();
        return back()->with('success', 'Lineman set on leave.');
    }

    public function backFromLeave($id) {
       $lineman = LineMan::findOrFail($id);

        if ($lineman->status === 'on_leave') {
            $lineman->status = 'active';
            $lineman->save();

            return redirect()->back()->with('success', $lineman->first_name . ' is now back from leave.');
        }

        return redirect()->back()->with('error', 'Cannot return this lineman from leave.');
    }


    public function archive(LineMan $lineman , $id) {
        $lineman->status = 'archived';
        $lineman->save();
        return back()->with('success', 'Lineman archived.');
    }









}
