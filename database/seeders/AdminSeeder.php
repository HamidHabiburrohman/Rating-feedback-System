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
                'employee_id' => 'EMP-001',
                'department' => 'IT',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
                'two_factor_enabled' => false,
                'login_count' => 0,
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
                'bio' => 'General administrator responsible for daily operations.',
                'location' => 'Bandung, Indonesia',
                'employee_id' => 'EMP-002',
                'department' => 'Operations',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
                'two_factor_enabled' => false,
                'login_count' => 0,
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
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'phone' => '081234567892',
                'position' => 'Content Manager',
                'bio' => 'Content manager specializing in unit information.',
                'location' => 'Jakarta, Indonesia',
                'employee_id' => 'EMP-003',
                'department' => 'Content',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
                'two_factor_enabled' => false,
                'login_count' => 0,
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
                'bio' => 'Manages laboratory units and schedules.',
                'location' => 'Bandung, Indonesia',
                'employee_id' => 'EMP-004',
                'department' => 'Laboratory',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
                'two_factor_enabled' => false,
                'login_count' => 0,
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
                'bio' => 'Manages library facilities and reservations.',
                'location' => 'Bandung, Indonesia',
                'employee_id' => 'EMP-005',
                'department' => 'Library',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
                'two_factor_enabled' => false,
                'login_count' => 0,
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
                'bio' => 'Oversees clinic operations and health services.',
                'location' => 'Bandung, Indonesia',
                'employee_id' => 'EMP-006',
                'department' => 'Healthcare',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
                'two_factor_enabled' => false,
                'login_count' => 0,
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