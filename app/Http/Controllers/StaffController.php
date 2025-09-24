<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{

public function index(Request $request)
{
    $role   = $request->input('role', 'all');
    $status = $request->input('status', 'all');
    $query  = $request->input('query', '');

    $users = User::query();


    if (!empty($query)) {
        $users->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        });
    }


    if ($role !== 'all') {
        $users->where('role', $role);
    }


    if ($status !== 'all') {
        $users->where('status', $status);
    }

   
    $archivedUsers = User::where('archived', 1)
                        ->orderByDesc('updated_at')
                        ->paginate(3);

 
    $users = $users->where('archived', 0) 
                   ->paginate(5)
                   ->withQueryString();

    return view('pages.staffManagement', [
        'users'        => $users,
        'archivedUsers'=> $archivedUsers,
        'query'        => $query,
        'role'         => $role,
        'status'       => $status,
        'page'         => $users->currentPage(),
        'totalPages'   => $users->lastPage(),
    ]);
}










    

public function storeStaff(Request $request){
   $request->validate([
        'name' => 'required',
        'email' => 'required|unique:users,email',
        'phone' => 'required|max:12',
        'address' => 'required',
        'password' => [
            'required',
            'string',
            'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/'
        ],
        'role' => 'required|in:admin,staff,lineman'
    ], [
     
        'password.regex' => 'Password must be at least 8 characters, include an uppercase letter, a number, and a special character (@$!%*?&).'
    ]);

        User::Create([

           'name' => $request -> name,
           'email' => $request -> email,
           'phone' => $request -> phone,
           'address' => $request -> address,
           'password' =>  bcrypt($request->password),
           'role'  => $request ->role ,
           'status' => 'active',   
           'archived' => false,    
        ]);
        return redirect()->back()->with('success', 'Staff added successfully!');

    }



            public function checkEmail(Request $request)
            {
                $email = $request->query('email');
                $exists = User::where('email', $email)->exists();

                return response()->json([
                    'available' => !$exists
                ]);
            }




public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'password' => [
            'nullable',
            'string',
            'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/'
        ],
        'role' => 'required|in:admin,staff,lineman',
    ]);

    
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->phone = $validatedData['phone'] ?? $user->phone;
    $user->address = $validatedData['address'] ?? $user->address;
    $user->role = $validatedData['role'];

 
    if (!empty($validatedData['password'])) {
        $user->password = bcrypt($validatedData['password']);
    }

    $user->save();

    return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
}





public function search(Request $request)
{
    $query  = $request->get('query', '');
    $role   = $request->get('role', 'all');
    $status = $request->get('status', 'all');

    $users = User::where('role', '!=', 'admin')
        ->where('archived', 0);

    if ($role !== 'all') {
        $users->where('role', $role);
    }

    if ($status !== 'all') {
        $users->where('status', $status);
    }

    if ($query) {
        $users->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%")
              ->orWhere('address', 'like', "%{$query}%");
        });
    }

    $users = $users->paginate(3)->withQueryString();

    if ($request->ajax()) {
        return response()->json([
            'data'       => $users->items(),
            'pagination' => (string) $users->links('pagination::simple-tailwind'),
            'total'      => $users->total(),  
            'count'      => $users->count(),   
        ]);
    }

    return view('pages.staffManagement', compact('users', 'query', 'role', 'status'));
}



public function getArchivedStaff()
{
    $archivedUsers = User::where('archived', 1)
                         ->orderByDesc('updated_at')
                         ->paginated(3);

    return response()->json($archivedUsers);
}




public function toggleStatus(Request $request, $id)
{
    $user = User::findOrFail($id);
    $newStatus = $request->input('status');

    if (in_array($newStatus, ['active', 'inactive', 'leave'])) {
        $user->status = $newStatus;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    return response()->json(['success' => false, 'message' => 'Invalid status']);
}

public function archive($id)
{
    $user = User::findOrFail($id);
    $user->archived = 1;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User archived',
        'user' => $user 
    ]);
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
