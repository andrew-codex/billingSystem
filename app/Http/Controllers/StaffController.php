<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\StaffWelcome;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class StaffController extends Controller
{

public function index(Request $request)
{
    $searchUser = trim($request->input('searchUser'));
    $status     = $request->input('status', 'all'); 
    $role       = 'staff'; 
    $pageMain   = $request->input('page_main', 1);

    $query = User::query()
        ->where('archived', 0)
        ->where('role', $role)
        ->when($status !== 'all', fn($q) => $q->where('status', $status))
        ->when($searchUser, fn($q) => $q->where(function($q2) use ($searchUser) {
            $q2->where('first_name', 'like', "%{$searchUser}%")
               ->orWhere('last_name', 'like', "%{$searchUser}%")
               ->orWhere('email', 'like', "%{$searchUser}%")
               ->orWhere('status', 'like', "%{$searchUser}%");
        }))
        ->orderByDesc('created_at');

    $users = $query->paginate(10, ['*'], 'page_main');

    $cities    = json_decode(file_get_contents(public_path('json/city.json')), true);
    $barangays = json_decode(file_get_contents(public_path('json/barangay.json')), true);

    $users->getCollection()->transform(function($user) use ($cities, $barangays) {
        $city = collect($cities)->firstWhere('city_code', $user->city_code);
        $user->city_name = $city['city_name'] ?? '';
        $barangay = collect($barangays)->firstWhere('brgy_code', $user->barangay_code);
        $user->barangay_name = $barangay['brgy_name'] ?? '';
        return $user;
    });

    $archivedUsers = User::where('archived', 1)
        ->where('role', 'staff')
        ->orderByDesc('created_at')
        ->paginate(10, ['*'], 'page_archive');

    if ($request->ajax()) {
        $html = view('pages.staffManagement', compact('users', 'archivedUsers'))->render();
        return response()->json(['html' => $html]);
    }

    return view('pages.staffManagement', compact('users', 'archivedUsers'));
}

 

public function storeStaff(Request $request)
{
    $validated = $request->validate([
        'first_name'          => 'nullable',
        'last_name'              => 'nullable',
        'middle_name'          => 'nullable',
        'suffix'        => 'nullable',
        'email'         => 'required|unique:users,email',
        'phone'         => 'required|max:12',
        'city_code'     => 'nullable|string|max:255',
        'city_name'     => 'nullable|string|max:255',
        'barangay_code' => 'nullable|string|max:255',
        'barangay_name' => 'nullable|string|max:255',
        'role'          => 'required|in:admin,staff',

    ]);

    $plainPassword = Str::random(8);

    $user = User::create([
        'first_name'          => $request->first_name,
        'last_name'  => $request->last_name,
        'middle_name' => $request->middle_name,
        'suffix'    => $request->suffix,
        'email'         => $request->email,
        'phone'         => $request->phone,
        'city_code'     => $request->city_code,
        'city_name'     => $request->city_name,
        'barangay_code' => $request->barangay_code,
        'barangay_name' => $request->barangay_name,
        'password'      => Hash::make($plainPassword),
        'role'          => $validated['role'],   
        'status'        => 'active',   
        'archived'      => false,  
         'must_change_password' => true,  
    ]);

 
    Mail::to($user->email)->send(new StaffWelcome($user, $plainPassword));

    return redirect()->back()->with('success', 'Staff added successfully!');
}




public function checkEmail(Request $request)
{
    $email = $request->query('email');
    $userId = $request->query('user_id'); 

    $query = User::where('email', $email);

 
    if ($userId) {
        $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'available' => !$exists
    ]);
}



public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
         'last_name' => 'required|string|max:255',
          'middle_name' => 'required|string|max:255',
          'suffix'        => 'nullable',

        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
       'city_code' => 'nullable|string|max:255',
            'city_name' => 'nullable|string|max:255',
               'barangay_code' => 'nullable|string|max:255',
            'barangay_name' => 'nullable|string|max:255',
        'password' => [
            'nullable',
            'string',
            'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/'
        ],
        'role' => 'required|in:admin,staff,lineman',
    ]);

    $cities = json_decode(file_get_contents(public_path('json/city.json')), true);
    $barangays = json_decode(file_get_contents(public_path('json/barangay.json')), true);

    $city_code = $request->city_code;
    $city_name = collect($cities)->firstWhere('city_code', $city_code)['city_name'] ?? null;

    $barangay_code = $request->barangay_code;
    $barangay_name = collect($barangays)->firstWhere('brgy_code', $barangay_code)['brgy_name'] ?? null;

    
    $user->first_name = $validatedData['first_name'];
    $user->last_name = $validatedData['last_name'];
    $user->middle_name = $validatedData['middle_name'];
    $user->suffix = $validatedData['suffix'];
    $user->email = $validatedData['email'];
    $user->phone = $validatedData['phone'] ?? $user->phone;
    $user->city_code = $request->city_code;
    $user->city_name = $request->city_name;
    $user->barangay_code = $request->barangay_code;
    $user->barangay_name = $request->barangay_name;
    $user->role = $validatedData['role'];
    $user->save();

    return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
}



protected function authenticated(Request $request, $user)
{
    
    DB::table('sessions')
        ->where('user_id', $user->id)
        ->where('id', '!=', Session::getId())
        ->delete();
}

public function getArchivedStaff()
{
    $archivedUsers = User::where('archived', 1)
                         ->orderByDesc('updated_at')
                         ->paginated(10);

    return response()->json($archivedUsers);
}


public function toggleStatus(Request $request, $id)
{
    $user = User::findOrFail($id);
    $newStatus = $request->input('status');

    if (in_array($newStatus, ['active', 'inactive', 'leave'])) {
        $user->status = $newStatus;
        $user->save();

      
        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    return redirect()->back()->with('error', 'Invalid status.');
}


public function archive($id)
{
    $user = User::findOrFail($id);
    $user->archived = 1;
    $user->save();

  
    return redirect()->back()->with('success', 'User archived successfully!');
}


public function restore($id)
{
    User::where('id', $id)->update(['archived' => 0]);
    return redirect()->back()->with('success', 'User restored successfully.');
}


public function destroy($id)
{
    User::where('id', $id)->delete();
    return redirect()->back()->with('success', 'User permanently deleted.');
}

public function bulkRestore(Request $request)
{
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No users selected.');
    }

    User::whereIn('id', $ids)->update(['archived' => 0]);

    return redirect()->back()->with('success', count($ids) . ' user(s) restored successfully.');
}

public function bulkDelete(Request $request)
{
    $id = $request->input('id', []);

    if (empty($id)) {
        return redirect()->back()->with('error', 'No users selected.');
    }

    User::whereIn('id', $id)->delete();

    return redirect()->back()->with('success', count($id) . ' user(s) permanently deleted.');
}



}
