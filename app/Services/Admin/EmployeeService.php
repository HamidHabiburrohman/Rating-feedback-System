<?php

namespace App\Services\Admin;

use App\Models\Authentication\Employee;
use App\Models\Units\Unit;
use App\Models\Employee\EmployeeUnitAssignment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    public function getAll(): Collection
    {
        return Employee::with('unitAssignments')->get();
    }

    public function getActive(): Collection
    {
        return Employee::where('is_active', true)->get();
    }

    public function findById(int $id): ?Employee
    {
        return Employee::with('unitAssignments')->find($id);
    }

    public function create(array $data): Employee
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return Employee::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $employee = $this->findById($id);
        if (!$employee) {
            return false;
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $employee->update($data);
    }

    public function delete(int $id): bool
    {
        $employee = $this->findById($id);
        if (!$employee) {
            return false;
        }
        return $employee->delete();
    }

    public function restore(int $id): bool
    {
        $employee = Employee::withTrashed()->find($id);
        if (!$employee) {
            return false;
        }
        return $employee->restore();
    }

    public function assignToUnit(int $employeeId, int $unitId, int $adminId, ?string $role = null): EmployeeUnitAssignment
    {
        return EmployeeUnitAssignment::create([
            'employee_id' => $employeeId,
            'unit_id' => $unitId,
            'assigned_by_admin_id' => $adminId,
            'role_in_unit' => $role,
            'assigned_at' => now(),
            'is_active' => true,
        ]);
    }

    public function removeFromUnit(int $employeeId, int $unitId): bool
    {
        $assignment = EmployeeUnitAssignment::where('employee_id', $employeeId)
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->first();

        if (!$assignment) {
            return false;
        }

        $assignment->ended_at = now();
        $assignment->is_active = false;
        return $assignment->save();
    }

    public function getAssignments(int $employeeId): Collection
    {
        return EmployeeUnitAssignment::where('employee_id', $employeeId)
            ->with('unit')
            ->get();
    }

    public function getAssignedUnits(int $employeeId): Collection
    {
        return Unit::whereHas('employeeAssignments', function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId)
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                });
        })->get();
    }
}