<?php

namespace App\Http\Controllers;
use App\Models\BrownOutSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BrownoutScheduling extends Controller
{
    public function index() {
  $schedules = BrownOutSchedule::where('status', '!=', 'archived')
            ->orderBy('created_at', 'desc')
            ->get();

    $now = Carbon::now();

    foreach ($schedules as $schedule) {
        $start = Carbon::parse($schedule->schedule_date . ' ' . $schedule->start_time);
        $end = Carbon::parse($schedule->schedule_date . ' ' . $schedule->end_time);

    
        if ($schedule->status !== 'cancelled') {
            $newStatus = $schedule->status;

            if ($now->lt($start)) {
                $newStatus = 'upcoming';
            } elseif ($now->between($start, $end)) {
                $newStatus = 'ongoing';
            } elseif ($now->gt($end) && $now->gte($start)) {
             
                $newStatus = 'completed';
            }

            
            if ($schedule->status !== $newStatus) {
                $schedule->status = $newStatus;
                $schedule->save();
            }
        }
    }

    return view('pages.brownoutScheduling', compact('schedules'));
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

    public function updateSchedule(Request $request, $id){
        $schedule = BrownOutSchedule::findOrFail($id);

        $validated = $request->validate([
            'area' => 'required',
            'schedule_date' => 'required',
            'start_time' =>  'required',
            'end_time' => 'required',
            'description' => 'required'

        ]);

             $schedule ->update([
            'area' => $request->area,
             'schedule_date' => $request->schedule_date,
             'start_time' => $request->start_time,
             'end_time' => $request->end_time,
             'description' => $request->description,

        ]);
         return redirect()->back()->with('success', 'Schedule update Successfuly.');



    }


    

    public function cancel($id)
    {
        $schedule = BrownOutSchedule::findOrFail($id);
        $schedule->status = 'cancelled';
        $schedule->save();

        return back()->with('success', 'Schedule has been cancelled.');
    }

    public function archive($id)
    {
        $schedule = BrownOutSchedule::findOrFail($id);
        $schedule->status = 'archived';
        $schedule->save();

        return back()->with('success', 'Schedule has been archived.');
    }




}
