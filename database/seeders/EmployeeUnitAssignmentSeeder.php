<?php

namespace Database\Seeders;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Unit\Unit;
use Illuminate\Database\Seeder;

class EmployeeUnitAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $units = Unit::all();
        $admin = Admin::first();

        if ($employees->isEmpty() || $units->isEmpty()) {
            $this->command?->info('No employees or units found. Skipping EmployeeUnitAssignment seeding.');
            return;
        }

        foreach ($employees as $employee) {
            $assignedUnits = $units->random(min(rand(1, 3), $units->count()));

            foreach ($assignedUnits as $unit) {
                $exists = EmployeeUnitAssignment::where('employee_id', $employee->id)
                    ->where('unit_id', $unit->id)
                    ->where('is_active', true)
                    ->exists();

                if ($exists) {
                    continue;
                }

                EmployeeUnitAssignment::factory()
                    ->forEmployee($employee)
                    ->forUnit($unit)
                    ->create([
                        'assigned_by_admin_id' => $admin?->id,
                    ]);
            }
        }
    }
}