<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'phone' => '081234567890',
                'position' => 'System Administrator',
                'bio' => 'Super administrator with full system access and control over all features.',
                'location' => 'Bandung, Indonesia',
                'permissions' => json_encode(['*']),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'language' => 'id',
                    'notifications' => true,
                ]),
                'last_login_at' => null,
                'last_login_ip' => null,
            ],
            [
                'nama' => 'Admin Utama',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '081234567891',
                'position' => 'General Administrator',
                'bio' => 'General administrator responsible for daily operations and content management.',
                'location' => 'Bandung, Indonesia',
                'permissions' => json_encode(['manage_units', 'manage_ratings', 'view_reports']),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'language' => 'id',
                    'notifications' => true,
                ]),
                'last_login_at' => null,
                'last_login_ip' => null,
            ],
            [
                'nama' => 'Mas Amba',
                'email' => 'amba@suki.com',
                'password' => Hash::make('amba'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '081234567892',
                'position' => 'Content Manager',
                'bio' => 'Content manager specializing in unit information and facility updates.',
                'location' => 'Jakarta, Indonesia',
                'permissions' => json_encode(['manage_units', 'manage_facilities']),
                'preferences' => json_encode([
                    'theme' => 'dark',
                    'language' => 'en',
                    'notifications' => false,
                ]),
                'last_login_at' => null,
                'last_login_ip' => null,
            ],
            [
                'nama' => 'Admin Unit Lab',
                'email' => 'lab@admin.com',
                'password' => Hash::make('password'),
                'role' => 'unit',
                'email_verified_at' => now(),
                'phone' => '081234567893',
                'position' => 'Laboratory Coordinator',
                'bio' => 'Responsible for managing laboratory units, equipment inventory, and lab schedules.',
                'location' => 'Bandung, Indonesia',
                'permissions' => json_encode(['manage_lab_units', 'manage_schedules']),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'language' => 'id',
                    'notifications' => true,
                ]),
                'last_login_at' => null,
                'last_login_ip' => null,
            ],
            [
                'nama' => 'Admin Unit Perpustakaan',
                'email' => 'library@admin.com',
                'password' => Hash::make('password'),
                'role' => 'unit',
                'email_verified_at' => now(),
                'phone' => '081234567894',
                'position' => 'Library Manager',
                'bio' => 'Managing library facilities, book collections, and reading room reservations.',
                'location' => 'Bandung, Indonesia',
                'permissions' => json_encode(['manage_library_units', 'manage_reservations']),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'language' => 'id',
                    'notifications' => true,
                ]),
                'last_login_at' => null,
                'last_login_ip' => null,
            ],
            [
                'nama' => 'Admin Unit Klinik',
                'email' => 'clinic@admin.com',
                'password' => Hash::make('password'),
                'role' => 'unit',
                'email_verified_at' => now(),
                'phone' => '081234567895',
                'position' => 'Clinic Administrator',
                'bio' => 'Overseeing campus clinic operations, medical equipment, and health services.',
                'location' => 'Bandung, Indonesia',
                'permissions' => json_encode(['manage_clinic_units', 'manage_appointments']),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'language' => 'id',
                    'notifications' => true,
                ]),
                'last_login_at' => null,
                'last_login_ip' => null,
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }

        Admin::factory(3)->create();

        $this->command->info('AdminSeeder completed successfully!');
    }
}