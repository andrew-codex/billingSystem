<?php

namespace App\Http\Controllers;
use App\Models\BrownOutSchedule;
use Illuminate\Http\Request;

class BrownoutScheduling extends Controller
{
    public function index(){
        return view ('pages.brownoutScheduling');
    }


    public function storeSchedule(Request $request) {
        $validated =$request->validate ([

            'area' => 'required',
            'schedule_date' => 'required',
            'start_time' =>  'required',
            'end_time' => 'required',
            'description' => 'required'


        ]);


         $schedule = BrownOutSchedule::Create([
            'area' => $request->area,
             'schedule_date' => $request->schedule_date,
             'start_time' => $request->start_time,
             'end_time' => $request->end_time,
             'description' => $request->description,

        ]);

        return redirect()->back()->with('success', 'Schedule added Successfuly.');


    }



}
