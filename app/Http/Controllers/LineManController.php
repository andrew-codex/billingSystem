<?php

namespace App\Http\Controllers;

use App\Models\LineMan;
use Illuminate\Http\Request;

class LineManController extends Controller
{
    public function createLineMan(Request $request){
        $request->validate([
             'first_name' => 'required',
             'last_name' => 'required',
             'middle_name' => 'nullable',
             'suffix' => 'nullable',
            'region_code' => 'nullable|string|max:255',
            'province_code' => 'nullable|string|max:255',
            'city_code' => 'nullable|string|max:255',
            'barangay_code' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
             'availability' => 'nullable',
             'contact_number' => 'required',

             
        ]);

        Lineman::Create([
           'first_name' =>$request->first_name,
           'last_name' =>$request->last_name,
           'middle_name' =>$request->middle_name,
           'suffix' => $request->suffix,
        'region_code' => $request->region_code,
        'province_code' => $request->province_code,
        'city_code' => $request->city_code,
        'barangay_code' => $request->barangay_code,
        'street' => $request->street,
           'contact_number' => $request->contact_number,
           'status' => 'active',

        ]);

        return redirect()->route('reconnection.index')->with('success', 'Line Man added succesfully. ');
    }
}
