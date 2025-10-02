<?php

namespace App\Http\Controllers;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function edit($role)
{
    $permissions = [
        'manage_staff',
        'manage_roles',
        'manage_bills',
        'approve_payments',
        'view_reports',
        'export_reports',
        'system_settings',
        'create_bills',
        'edit_bills',
        'record_payments',
        'view_customers'
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
