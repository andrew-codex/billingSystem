<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RolePermissionController extends Controller
{
    public function edit($role)
    {
        $permissions = Permission::all();

        $rolePermissions = DB::table('role_permission')
            ->where('role', $role)
            ->pluck('permission_id')
            ->toArray();

        return view('role_permissions.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $role)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        DB::table('role_permission')->where('role', $role)->delete();

   
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionId) {
                DB::table('role_permission')->insert([
                    'role' => $role,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Permissions updated for role: '.$role);
    }
}
