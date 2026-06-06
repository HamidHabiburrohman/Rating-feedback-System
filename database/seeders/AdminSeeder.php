<?php

namespace Database\Seeders;

use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    
    public function run(): void
    {
        Admin::create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@itn.ac.id',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Admin::create([
            'nama' => 'Admin Unit',
            'email' => 'admin@itn.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        Admin::factory(5)->create();
    }
}