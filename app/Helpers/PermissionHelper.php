<?php

use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;

if (!function_exists('hasPermission')) {
    function hasPermission($permission)
    {
        $user = Auth::user();
        if (!$user) return false;

      
        $rolePermission = RolePermission::where('role', $user->role)->first();

        if (!$rolePermission) return false;

        $permissions = $rolePermission->permissions ?? [];

        return in_array($permission, $permissions);
    }
}

if (!function_exists('getPermissionFromRoute')) {
    function getPermissionFromRoute($routeName)
    {
     
        $map = [
            'staff.index' => 'manage_staff',

            'dashboard.index' => 'view_dashboard',

            'billing.index' => 'manage_billings',
      
            'consumer.index' => 'manage_consumers',
            'consumer.create' => 'create_consumers',
            
            'reconnection.index' => 'manage_reconnection',

            'BrownoutScheduling.index' => 'manage_brownout',

            'electricMeter.index' => 'manage_inventory',

            'permissions.edit' => 'manage_roles',
            
            'permissions.update' => 'manage_roles',

        
        ];

        return $map[$routeName] ?? null;
    }
}

