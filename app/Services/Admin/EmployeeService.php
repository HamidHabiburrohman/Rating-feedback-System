<?php

namespace App\Services\Admin;

use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Employee\EmployeeUnitAssignment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeService extends BaseAdminService
{
    public function getFilteredEmployees(array $filters = [])
    {
        $query = Employee::with(['unitAssignments.unit']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function getFormData(): array
    {
        return Cache::tags(['employees', 'dropdown'])->remember('employee_form_data', 3600, function () {
            return [
                'units' => Unit::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']),
            ];
        });
    }

    public function create(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password'] ?? 'password123');
            $data['is_active'] = $data['is_active'] ?? true;

            $unitId = $data['unit_id'] ?? null;
            $roleInUnit = $data['role_in_unit'] ?? null;
            unset($data['unit_id'], $data['role_in_unit']);

            $employee = Employee::create($data);

            if ($unitId) {
                EmployeeUnitAssignment::create([
                    'employee_id' => $employee->id,
                    'unit_id' => $unitId,
                    'role_in_unit' => $roleInUnit,
                    'is_active' => true,
                    'started_at' => now(),
                ]);
            }

            Cache::tags(['employees', 'dashboard'])->flush();

            return $employee->fresh(['unitAssignments.unit']);
        });
    }

    public function getDetail(int $id): array
    {
        $cacheKey = "admin_employee_detail_{$id}";

        return Cache::tags(['employees', "employee_{$id}"])->remember($cacheKey, 300, function () use ($id) {
            $employee = Employee::with([
                'unitAssignments.unit',
            ])->findOrFail($id);

            return [
                'employee' => $employee,
                'stats' => [
                    'total_units' => $employee->unitAssignments()->where('is_active', true)->count(),
                ],
                'active_assignments' => $employee->unitAssignments()
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                    })
                    ->with('unit')
                    ->get(),
            ];
        });
    }

    public function getEditData(int $id): array
    {
        $employee = Employee::with(['unitAssignments.unit'])->findOrFail($id);
        $formData = $this->getFormData();

        return [
            'employee' => $employee,
            'units' => $formData['units'],
        ];
    }

    public function update(int $id, array $data): Employee
    {
        return DB::transaction(function () use ($id, $data) {
            $employee = Employee::findOrFail($id);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            unset($data['unit_id'], $data['role_in_unit']);

            $employee->update($data);

            Cache::tags(['employees', "employee_{$id}", 'dashboard'])->flush();

            return $employee->fresh(['unitAssignments.unit']);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $employee = Employee::findOrFail($id);

            $employee->unitAssignments()->update([
                'is_active' => false,
                'ended_at' => now(),
            ]);

            $employee->delete();

            Cache::tags(['employees', "employee_{$id}", 'dashboard'])->flush();
            return true;
        });
    }

    public function restore(int $id): Employee
    {
        return DB::transaction(function () use ($id) {
            $employee = Employee::onlyTrashed()->findOrFail($id);
            $employee->restore();

            Cache::tags(['employees', "employee_{$id}", 'dashboard'])->flush();
            return $employee;
        });
    }

    public function assignToUnit(int $employeeId, int $unitId, ?string $roleInUnit = null): EmployeeUnitAssignment
    {
        return DB::transaction(function () use ($employeeId, $unitId, $roleInUnit) {
            Employee::findOrFail($employeeId);
            Unit::findOrFail($unitId);

            $existing = EmployeeUnitAssignment::where('employee_id', $employeeId)
                ->where('unit_id', $unitId)
                ->where('is_active', true)
                ->first();

            if ($existing) {
                throw new \Exception('Karyawan sudah ditugaskan ke unit ini');
            }

            $assignment = EmployeeUnitAssignment::create([
                'employee_id' => $employeeId,
                'unit_id' => $unitId,
                'role_in_unit' => $roleInUnit,
                'is_active' => true,
                'started_at' => now(),
            ]);

            Cache::tags(['employees', "employee_{$employeeId}", 'units', "unit_{$unitId}"])->flush();

            return $assignment;
        });
    }

    public function removeFromUnit(int $employeeId, int $unitId): bool
    {
        return DB::transaction(function () use ($employeeId, $unitId) {
            $assignment = EmployeeUnitAssignment::where('employee_id', $employeeId)
                ->where('unit_id', $unitId)
                ->where('is_active', true)
                ->first();

            if (!$assignment) {
                throw new \Exception('Karyawan tidak ditugaskan ke unit ini');
            }

            $assignment->update([
                'is_active' => false,
                'ended_at' => now(),
            ]);

            Cache::tags(['employees', "employee_{$employeeId}", 'units', "unit_{$unitId}"])->flush();

            return true;
        });
    }

    public function getAssignedUnits(int $employeeId): array
    {
        $cacheKey = "admin_employee_assigned_units_{$employeeId}";

        return Cache::tags(['employees', "employee_{$employeeId}"])->remember($cacheKey, 300, function () use ($employeeId) {
            return Employee::findOrFail($employeeId)
                ->unitAssignments()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                })
                ->with('unit')
                ->get()
                ->toArray();
        });
    }
}
