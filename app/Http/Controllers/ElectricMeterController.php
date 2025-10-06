<?php

namespace App\Http\Controllers;
use App\Models\ElectricMeter;
use Illuminate\Http\Request;

class ElectricMeterController extends Controller
{



public function index(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');
    $pageMain = $request->input('page_main', 1);
    $pageArchive = $request->input('page_archive', 1);


    $electricMeters = ElectricMeter::query()
        ->where('status', '!=', 'archived')
        ->when($search, function($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('consumer_id', 'like', "%{$search}%")
                  ->orWhere('electric_meter_number', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        })
        ->when($status && $status !== 'all', function($query) use ($status) {
            $query->where('status', $status);
        })
        ->paginate(3, ['*'], 'page_main');

$searchArchive = $request->input('search_archive');

$archivedMeters = ElectricMeter::query()
    ->where('status', 'archived')
    ->when($searchArchive, function ($query, $searchArchive) {
        $query->where(function ($q) use ($searchArchive) {
            $q->where('consumer_id', 'like', "%{$searchArchive}%")
              ->orWhere('electric_meter_number', 'like', "%{$searchArchive}%")
              ->orWhere('status', 'like', "%{$searchArchive}%");
        });
    })
    ->paginate(2, ['*'], 'page_archive');


    if ($request->ajax()) {
        $html = view('pages.electricmeterInventory', compact('electricMeters', 'archivedMeters'))->render();
        return response()->json(['html' => $html]);
    }

    return view('pages.electricmeterInventory', compact('electricMeters', 'archivedMeters'));
}






    public function store(Request $request) {
        $request -> validate([
            'electric_meter_number' => 'required|unique:electric_meters,electric_meter_number',
            'status' 
        ]);

        ElectricMeter::create([
            'electric_meter_number' => $request->electric_meter_number,
           
        ]);

        return redirect()->route('electricMeter.index')->with('success','Electric Meter Added Successfully');
    }

    public function checkMeter(Request $request) {
        $exists = ElectricMeter::where('electric_meter_number', $request->electric_meter_number)->exists();

    return response()->json(['exists' => $exists]);

    }


    function update(Request $request, $id) {
        $request -> validate([
            'electric_meter_number' => 'required|unique:electric_meters,electric_meter_number,'.$id,
            'created_at' => 'required',
        ]);

        $meter = ElectricMeter::findOrFail($id);
        $meter->electric_meter_number = $request->electric_meter_number;
        $meter->created_at = $request->created_at;
        $meter->save();

        return redirect()->route('electricMeter.index')->with('success','Electric Meter Updated Successfully');
    }


    
public function updateMeter(Request $request, ElectricMeter $meter)
{
    $validated = $request->validate([
        'installation_date' => 'required|date',
        'house_type' => 'required|in:residential,commercial,industrial',
    ]);

    $meter->update([
        'installation_date' => $validated['installation_date'],
        'house_type' => $validated['house_type'],
    ]);

    return redirect()->back()->with('success', 'Meter details updated successfully.');
}

  


    public function archived($id)
    {
        $meter = ElectricMeter::findOrFail($id);

        if($meter->status !== 'damaged'){
            return redirect()->route('electricMeter.index')->with('error' ,' Cannot archive this meter because it is still assigned to a consumer.');
        }

        
        $meter->status = 'archived';
        $meter->save();

        return redirect()->route('electricMeter.index')->with('success', 'Electric Meter archived successfully.');
    }



  





    public function destroy($id){
        $electricMeters = ElectricMeter::findOrFail($id);
        if($electricMeters->consumer_id !==null){
             return redirect()->route('electricMeter.index')->with('error' ,' Cannot delete this meter because it is still assigned to a consumer.');

        }

        ElectricMeter::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Electric Meter Permanently deleted.');

    }


    public function bulkDelete(Request $request)
    {
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No item selected.');
    }

    ElectricMeter::whereIn('id', $ids)->delete();

    return redirect()->back()->with('success', count($ids) . ' Item(s) permanently deleted.');
   }

}
