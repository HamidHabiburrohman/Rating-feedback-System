<?php

namespace Database\Seeders;

use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class EmployeeUnitAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $units = Unit::all();
        $admin = Admin::first();

        if ($employees->isEmpty() || $units->isEmpty()) {
            $this->command->info('No employees or units found.');
            return;
        }

        foreach ($employees as $employee) {
            $assignedUnits = $units->random(min(rand(1, 3), $units->count()));

            foreach ($assignedUnits as $unit) {
                \App\Models\Employee\EmployeeUnitAssignment::create([
                    'employee_id' => $employee->id,
                    'unit_id' => $unit->id,
                    'assigned_by_admin_id' => $admin?->id,
                    'role_in_unit' => $this->getRandomRole(),
                    'assigned_at' => now(),
                    'is_active' => true,
                ]);
            }
        }
    }

    private function getRandomRole(): string
    {
        $roles = ['Kepala', 'Koordinator', 'Staff', 'Teknisi', 'Asisten'];
        return $roles[array_rand($roles)];
    }
}