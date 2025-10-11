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
            ['email' => 'Admin@gmail.com'], 
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@112203'), 
                'role' => 'admin',
                'status' => 'active',
                'archived' => false,
                'must_change_password' => false,
            ]
        );
    }
}
