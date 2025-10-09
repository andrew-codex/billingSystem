<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Group;
class ReconnectionController extends Controller
{
    public function index(Request $request)
    {
        $availableCount = LineMan::where('availability', 1)->count();

        $query = LineMan::with('group');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('group', function($g) use ($request) {
                      $g->where('group_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'inactive') {
                $query->where('status', 'inactive');
            } else {
                $query->whereHas('group', function($g) use ($request) {
                    $g->where('group_name', $request->status);
                });
            }
        }

        $linemen = $query->orderBy('last_name')->get();

        $groups = Group::select('id','group_name')
            ->withCount('linemen')
            ->orderBy('group_name')
            ->get();

       
        $groupedLinemen = $linemen->groupBy(function($lineman) {
            return $lineman->group && $lineman->group->group_name
                ? $lineman->group->group_name
                : 'No Group'; 
        });

        $cities = json_decode(file_get_contents(public_path('json/city.json')), true);
        $barangays = json_decode(file_get_contents(public_path('json/barangay.json')), true);

        $linemen->transform(function($lineman) use ($cities, $barangays) {
            $city = collect($cities)->firstWhere('city_code', $lineman->city_code);
            $lineman->city_name = $city['city_name'] ?? '';

            $barangay = collect($barangays)->firstWhere('brgy_code', $lineman->barangay_code);
            $lineman->barangay_name = $barangay['brgy_name'] ?? '';

            return $lineman;
        });

        return view('pages.reconnection', compact('linemen', 'availableCount', 'groups', 'groupedLinemen'));
    }
}
