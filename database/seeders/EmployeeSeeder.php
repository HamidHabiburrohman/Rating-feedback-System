<?php

namespace Database\Seeders;

use App\Models\Authentication\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([
            'name' => 'Karyawan Satu',
            'email' => 'employee1@itn.ac.id',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP-001',
            'is_active' => true,
            'position' => 'Kepala Laboratorium',
            'department' => 'Laboratorium Komputer',
        ]);

        Employee::create([
            'name' => 'Karyawan Dua',
            'email' => 'employee2@itn.ac.id',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP-002',
            'is_active' => true,
            'position' => 'Staff Perpustakaan',
            'department' => 'Perpustakaan',
        ]);

        Employee::factory(10)->create();
    }
}