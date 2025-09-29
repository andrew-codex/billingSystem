<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           User::firstOrCreate(
            ['email' => 'Admin@gmail.com'], // unique check
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@112203'), // your chosen password
                'role' => 'admin',
                'status' => 'active',
                'archived' => false,
                'must_change_password' => false, // ✅ admin will NOT be forced to change password
            ]
        );
    }
}
