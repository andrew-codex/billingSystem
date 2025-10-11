<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SyncPermissionsSeeder extends Seeder
{
    public function run()
    {
    
        $allPermissions = config('permissions');

   
        $roles = DB::table('role_permissions')->get();

        foreach ($roles as $role) {
            
            $existing = json_decode($role->permissions ?? '[]', true);

          
            $merged = array_unique(array_merge($existing, $allPermissions));

           
            DB::table('role_permissions')->where('id', $role->id)->update([
                'permissions' => json_encode($merged),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('All roles permissions synced successfully!');
    }
}
