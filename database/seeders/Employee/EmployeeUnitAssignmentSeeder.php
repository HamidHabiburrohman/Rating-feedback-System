<?php

namespace Database\Seeders\Employee;

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

        $activeStatuses = ['assigned', 'accepted', 'in_progress', 'waiting_verification'];

        foreach ($employees as $employee) {
            $assignedUnits = $units->random(min(rand(1, 3), $units->count()));

            foreach ($assignedUnits as $unit) {
                $exists = EmployeeUnitAssignment::where('employee_id', $employee->id)
                    ->where('unit_id', $unit->id)
                    ->whereIn('status', $activeStatuses)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $status = fake()->randomElement(['assigned', 'accepted', 'in_progress', 'completed', 'cancelled']);
                $completedAt = $status === 'completed' ? fake()->dateTimeBetween('-3 months', '-1 day') : null;
                $verifiedAt = $status === 'completed' && fake()->boolean(50) ? fake()->dateTimeBetween('-2 months', 'now') : null;

                EmployeeUnitAssignment::factory()
                    ->forEmployee($employee)
                    ->forUnit($unit)
                    ->create([
                        'assigned_by_admin_id' => $admin?->id,
                        'verified_by_admin_id' => $verifiedAt ? $admin?->id : null,
                        'status' => $status,
                        'priority' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
                        'notes' => fake()->optional(0.3)->sentence(),
                        'assigned_at' => fake()->dateTimeBetween('-6 months', 'now'),
                        'started_at' => in_array($status, ['accepted', 'in_progress', 'completed']) ? fake()->dateTimeBetween('-5 months', 'now') : null,
                        'completed_at' => $completedAt,
                        'verified_at' => $verifiedAt,
                    ]);
            }
        }
    }
}