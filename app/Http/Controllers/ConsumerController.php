<?php

namespace App\Http\Controllers;
use App\Models\Consumer;
use App\Models\ElectricMeter;
use App\Models\MeterTransferHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsumerWelcome;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConsumerController extends Controller
{
  public function index(Request $request)
{
    $searchConsumer = trim($request->input('searchConsumer'));
    $status = $request->input('status');
    $house_type = $request->input('house_type');
    $pageMain = $request->input('page_main', 1);
    $pageArchive = $request->input('page_archive', 1);

    $meters = ElectricMeter::whereNull('consumer_id')
        ->where('status', 'unassigned')
        ->get();

   
    $query = Consumer::query()
        ->where('status', '!=', 'archived')
        ->orderBy('created_at', 'desc')
        ->when($searchConsumer, function ($query, $searchConsumer) {
            $query->where(function ($q) use ($searchConsumer) {
                $q->where('id', 'like', "%{$searchConsumer}%")
                  ->orWhere('first_name', 'like', "%{$searchConsumer}%")
                  ->orWhere('last_name', 'like', "%{$searchConsumer}%")
                  ->orWhere('status', 'like', "%{$searchConsumer}%")
                  ->orWhereHas('electricMeters', function ($meterQuery) use ($searchConsumer) {
                      $meterQuery->where('electric_meter_number', 'like', "%{$searchConsumer}%");
                  });
            });
        })
        ->when($status && $status !== 'all', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($house_type && $house_type !== 'all', function ($query) use ($house_type) {
            $query->where('house_type', $house_type);
        });

    
    $consumers = $query->paginate(5, ['*'], 'page_main');


    $regions = json_decode(file_get_contents(public_path('json/region.json')), true);
    $provinces = json_decode(file_get_contents(public_path('json/province.json')), true);
    $cities = json_decode(file_get_contents(public_path('json/city.json')), true);
    $barangays = json_decode(file_get_contents(public_path('json/barangay.json')), true);

    $consumers->getCollection()->transform(function($consumer) use ($regions, $provinces, $cities, $barangays) {
        $region = collect($regions)->firstWhere('region_code', $consumer->region_code);
        $consumer->region_name = $region['region_name'] ?? $consumer->region_name ?? '';

        $province = collect($provinces)->firstWhere('province_code', $consumer->province_code);
        $consumer->province_name = $province['province_name'] ?? $consumer->province_name ?? '';

        $city = collect($cities)->firstWhere('city_code', $consumer->city_code);
        $consumer->city_name = $city['city_name'] ?? $consumer->city_name ?? '';

        $barangay = collect($barangays)->firstWhere('brgy_code', $consumer->barangay_code);
        $consumer->barangay_name = $barangay['brgy_name'] ?? $consumer->barangay_name ?? '';

        return $consumer;
    });


    $archivedConsumers = Consumer::where('status', 'archived')
        ->paginate(5, ['*'], 'page_archive');

  
    if ($request->ajax()) {
        $html = view('pages.consumerManagement', compact('meters', 'consumers', 'archivedConsumers'))->render();
        return response()->json(['html' => $html]);
    }

    return view('pages.consumerManagement', compact('meters', 'consumers', 'archivedConsumers'));
}






 public function store(Request $request)
{
    $validated = $request->validate([
        'first_name'   => 'required|string|max:50',
        'last_name'    => 'required|string|max:50',
        'middle_name'  => 'nullable|string|max:50',
        'suffix' => 'nullable|in:Jr.,Sr.,II,III,IV',
        'email' => 'required|email|unique:consumers,email',
        'phone' => [
            'nullable',
            'unique:consumers,phone',
            'regex:/^(09\d{9}|\+639\d{9})$/',
        ],
        'city_name' => 'nullable|string|max:255',
        'city_code' => 'nullable|string|max:255',
        'barangay_code' => 'nullable|string|max:255',
        'barangay_name' => 'nullable|string|max:255',
        'street' => 'nullable|string|max:255',
        'installation_date' => 'nullable|date',
        'house_type' => 'nullable|string|in:residential,commercial,industrial',
        'status' => 'nullable|in:active,inactive,archived',
        'electric_meter_number' => [
            'nullable',
            'exists:electric_meters,id',
            function ($attribute, $value, $fail) {
                if (ElectricMeter::where('id', $value)->whereNotNull('consumer_id')->exists()) {
                    $fail("The selected meter (ID: $value) is already assigned to another consumer.");
                }
            }
        ],
    ]);

     $plainPassword = Str::random(10); 


    $consumer = Consumer::create([
        'first_name'  => $validated['first_name'],
        'last_name'  => $validated['last_name'],
        'middle_name'  => $validated['middle_name'],
        'suffix'  => $validated['suffix'],
        'email'      => $validated['email'],
          'city_code' => $request->city_code,
       'city_name' => $request->city_name,
        'barangay_code' => $request->barangay_code,
        'barangay_name' => $request->barangay_name,
         'street' => $validated['street'],
        'phone'      => $validated['phone'] ?? null,
          'password' => Hash::make($plainPassword),
        'house_type' => $validated['house_type'],
        'installation_date' => $validated['installation_date'],
        'must_change_password' => true,
    ]);

    
    ElectricMeter::where('id', $validated['electric_meter_number'])
        ->update([
            'consumer_id' => $consumer->id,
            'status' => 'active',
            'installation_date' => $request->installation_date,
        ]);

        Mail::to($consumer->email)->send(new ConsumerWelcome($consumer, $plainPassword));

    return redirect()->route('consumer.index')
        ->with('success', 'Consumer added successfully with an assigned meter.');
}

    



public function checkEmail(Request $request)
{
    $query = Consumer::where('email', $request->email);

    
    if ($request->has('ignore_id')) {
        $query->where('id', '!=', $request->ignore_id);
    }

    $exists = $query->exists();

    return response()->json(['exists' => $exists]);
}






public function update(Request $request, $id)
{
    $consumer = Consumer::findOrFail($id);

    $validated = $request->validate([
        'first_name'   => 'required|string|max:50',
        'last_name'    => 'required|string|max:50',
        'middle_name'  => 'nullable|string|max:50',
        'suffix'       => 'nullable|in:Jr.,Sr.,II,III,IV',

     
 
        'city_code'     => 'nullable|string|max:20',
        'barangay_code' => 'nullable|string|max:20',


        'city_name'     => 'nullable|string|max:255',
        'barangay_name' => 'nullable|string|max:255',

        'street'        => 'nullable|string|max:255',
      

        'email' => 'required|email|unique:consumers,email,' . $consumer->id,
        'phone' => [
            'nullable',
            'regex:/^(09\d{9}|\+639\d{9})$/',
            'unique:consumers,phone,' . $consumer->id,
        ],

        'installation_date' => 'nullable|date',
        'house_type'        => 'nullable|in:residential,commercial,industrial',
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

 
    $consumer->update([
        'first_name'      => $validated['first_name'],
        'last_name'       => $validated['last_name'],
        'middle_name'     => $validated['middle_name'],
        'suffix'          => $validated['suffix'],
        'email'           => $validated['email'],
        'city_code'       => $city_code,
        'city_name'       => $city_name,
        'barangay_code'   => $barangay_code,
        'barangay_name'   => $barangay_name,
        'street'          => $validated['street'],
        'phone'           => $validated['phone'] ?? null,
        'house_type'      => $validated['house_type'] ?? null,
    ]);

    
    if ($request->filled('installation_date')) {
        $activeMeter = $consumer->electricMeters()->where('status', 'active')->first();
        if ($activeMeter) {
            $activeMeter->installation_date = $validated['installation_date'];
            $activeMeter->save();
        }
    }

    return redirect()->route('consumer.index')->with('success', 'Consumer updated successfully!');
}


public function archived($id){
    $consumer = Consumer::findOrfail($id);

    if($consumer->electricMeters()->exists()){
        return redirect()->route('consumer.index')->with('error','Consumer cannot be archived because it has a linked electric meter.');
    }


    $consumer ->status = 'archived';
    $consumer->save();

    return redirect()->route('consumer.index')->with('success', 'Consumer archived successfully.');
}


public function unArchived($id){
    $archivedConsumer = Consumer::findOrfail($id);


    $archivedConsumer->status = 'active';
    $archivedConsumer->save();

    return redirect()->route('consumer.index')->with('success', 'Consumer restore successfully.');



}


public function destroyConsumer($id){
    $consumer = Consumer::findOrFail($id);

    Consumer::where('id', $id)->delete();

    return redirect()->route('consumer.index')->with('success', 'Consumer deleted successfully.');
}



public function assign(Request $request, Consumer $consumer)
{
    $request->validate([
        'meter_id' => 'required|exists:electric_meters,id',
        'installation_date' => 'required|date',
    ]);

    $meter = ElectricMeter::findOrFail($request->meter_id);
    $meter->consumer_id = $consumer->id;
    $meter->installation_date = $request->installation_date;
    $meter->status = 'active';
    $meter->save();


    ConsumerMeterHistory::create([
        'consumer_id' => $consumer->id,
        'meter_id' => $meter->id,
        'transaction_type' => 'assignment',
        'remarks' => "New meter {$meter->electric_meter_number} assigned",
        'changed_by' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Meter assigned successfully!');
}









}
