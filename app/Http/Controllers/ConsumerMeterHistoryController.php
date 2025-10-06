<?php

namespace App\Http\Controllers;
use App\Models\Consumer;
use App\Models\ElectricMeter;
use App\Models\ConsumerMeterHistory;
use Illuminate\Http\Request;

class ConsumerMeterHistoryController extends Controller
{


public function transferForm(ElectricMeter $meter)
{
  


    $meters = ElectricMeter::whereNull('consumer_id')
        ->where('status', 'unassigned')
        ->get();


    $consumers = Consumer::where('id', '!=', $meter->consumer_id)
    ->where('status', '!=', 'archived')
    ->get();


    $history = ConsumerMeterHistory::where('consumer_id', $meter->consumer_id)
        ->with(['meter', 'changedBy'])
        ->latest()
        ->take(5)
        ->get();

  
    return view('pages.transferForm', compact('meter', 'consumers', 'history', 'meters'));
}



    

    public function transferOrReplace(Request $request, ElectricMeter $meter)
{
    $validated = $request->validate([
        'mode'         => 'required|in:transfer,replacement',
        'consumer_id'  => 'required_if:mode,transfer|nullable|exists:consumers,id',
        'new_meter_no' => 'required_if:mode,replacement|nullable|exists:electric_meters,electric_meter_number',
        'house_type'   => 'required|in:residential,commercial,industrial',
    ]);

    if ($validated['mode'] === 'transfer') {
        // --- TRANSFER MODE ---
        $oldOwnerId   = $meter->consumer_id;
        $oldOwnerName = Consumer::find($oldOwnerId)?->full_name ?? 'Unknown';
        $newOwner     = Consumer::find($validated['consumer_id']);
        $newOwnerName = $newOwner?->full_name ?? 'Unknown';

        $meter->update([
            'consumer_id'       => $validated['consumer_id'],
            'house_type'        => $validated['house_type'],
            'status'            => 'active',
            'installation_date' => now(),
        ]);

        ConsumerMeterHistory::create([
            'consumer_id'      => $validated['consumer_id'],
            'meter_id'         => $meter->id,
            'transaction_type' => 'transfer',
            'start_date'       => now(),
            'remarks'          => "Transferred from {$oldOwnerName} (ID {$oldOwnerId}) to {$newOwnerName} (ID {$validated['consumer_id']})",
            'changed_by'       => auth()->id(),
        ]);
    } 
    else {
        // --- REPLACEMENT MODE ---
        $oldMeterNo = $meter->electric_meter_number;
        $newMeter   = ElectricMeter::where('electric_meter_number', $validated['new_meter_no'])->first();

        if (!$newMeter) {
            return back()->withErrors(['new_meter_no' => 'Invalid meter selected.']);
        }

        $oldConsumerId = $meter->consumer_id;

        $newMeter->update([
            'consumer_id'       => $oldConsumerId,
            'status'            => 'active',
            'installation_date' => now(),
            'house_type'        => $validated['house_type'],
        ]);

        $meter->update([
            'status'       => 'damaged',
            'consumer_id'  => null,
            'end_date'     => now(),
        ]);

        ConsumerMeterHistory::create([
            'consumer_id'      => $oldConsumerId,
            'meter_id'         => $meter->id,
            'transaction_type' => 'replacement',
            'start_date'       => now(),
            'remarks'          => "Replaced meter {$oldMeterNo} with {$newMeter->electric_meter_number}",
            'changed_by'       => auth()->id(),
        ]);
    }

    return redirect()->route('consumer.index')->with('success', 'Operation completed successfully.');
}




        public function assignMeter(Request $request, Consumer $consumer)
            {
                $request->validate([
                    'meter_id' => 'required|exists:electric_meters,id',
                      'house_type' => 'required|in:residential,commercial,industrial',
                ]);

                $meter = ElectricMeter::findOrFail($request->meter_id);


                $meter->update([
                    'consumer_id'       => $consumer->id,
                    'status'            => 'active',
                  'house_type'        => $request->house_type,
                    'installation_date' => now(),
                ]);

                 


            
                ConsumerMeterHistory::create([
                    'consumer_id'      => $consumer->id,
                    'meter_id'         => $meter->id,
                    'transaction_type' => 'assignment',
                    'start_date'       => now(),
                    'remarks'          => "Assigned meter {$meter->electric_meter_number} to {$consumer->full_name} (ID {$consumer->id})",
                    'changed_by'       => auth()->id(),
                ]);

                return redirect()->back()->with('success', 'Meter assigned successfully.');
            }











}
