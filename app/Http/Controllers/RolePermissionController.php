<?php

namespace App\Http\Controllers;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function edit($role)
{
    $permissions = [
    'view_dashboard',

    'manage_consumers',
    'create_consumers',

    'manage_staff',

    'manage_billings',

    'manage_reconnection',

    'manage_brownout',

    'manage_inventory',

    'manage_roles',
     
    ];

    $rolePermission = RolePermission::where('role', $role)->first();

 
        $savedPermissions = $rolePermission ? $rolePermission->permissions : [];

    return view('pages.role_permissions.edit', compact('permissions', 'role', 'savedPermissions'));
}


         public function update(Request $request, $role)
            {
                $validated = $request->validate([
                    'permissions' => 'array'
                ]);

                $permissions = $validated['permissions'] ?? [];

                RolePermission::updateOrCreate(
                    ['role' => $role],
                    ['permissions' => $permissions]
                );

                return redirect()
                    ->back()
                    ->with('success', "Permissions updated for {$role}!");
            }


}
