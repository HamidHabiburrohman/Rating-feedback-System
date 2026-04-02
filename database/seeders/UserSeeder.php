<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ],
            [
                'nama' => 'Admin Utama',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'nama' => 'Mas Amba',
                'email' => 'amba@suki.com',
                'password' => Hash::make('amba'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'nama' => 'Admin Unit Lab',
                'email' => 'lab@admin.com',
                'password' => Hash::make('password'),
                'role' => 'unit',
                'email_verified_at' => now(),
            ],
            [
                'nama' => 'Admin Unit Perpustakaan',
                'email' => 'library@admin.com',
                'password' => Hash::make('password'),
                'role' => 'unit',
                'email_verified_at' => now(),
            ],
            [
                'nama' => 'Admin Unit Klinik',
                'email' => 'clinic@admin.com',
                'password' => Hash::make('password'),
                'role' => 'unit',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        User::factory(5)->create();
    }
}