<?php

declare(strict_types=1);

namespace App\Services\Admin\Employee;

use App\Models\Authentication\Employee;
use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Unit\Unit;
use App\Services\Admin\Shared\BaseAdminService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Employee\WelcomeMail;

class EmployeeService extends BaseAdminService
{
    public function getFilteredEmployees(array $filters = [])
    {
        $query = Employee::with(['employeeAssignments.unit']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['unit_id'])) {
            $unitIds = is_array($filters['unit_id'])
                ? $filters['unit_id']
                : explode(',', $filters['unit_id']);

            $query->whereHas('employeeAssignments', function ($q) use ($unitIds) {
                $q->whereIn('unit_id', $unitIds)
                    ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                    ->whereNull('completed_at');
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $statuses = is_array($filters['status'])
                ? $filters['status']
                : explode(',', $filters['status']);

            if (in_array('active', $statuses) && !in_array('inactive', $statuses)) {
                $query->where('is_active', true);
            } elseif (in_array('inactive', $statuses) && !in_array('active', $statuses)) {
                $query->where('is_active', false);
            }
        }

        $sortField = $filters['sort'] ?? 'name';
        $sortOrder = $filters['order'] ?? 'asc';
        $allowedSorts = ['name', 'created_at'];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc'])
            ? strtolower($sortOrder)
            : 'asc';

        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getUnitsForFilter()
    {
        return Cache::tags(['employees', 'dropdown'])->remember(
            'employee_filter_units',
            3600,
            function () {
                return Unit::where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name']);
            }
        );
    }

    public function getFormData(): array
    {
        return Cache::tags(['employees', 'dropdown'])->remember(
            'employee_form_data',
            3600,
            function () {
                return [
                    'units' => Unit::where('is_active', true)
                        ->orderBy('name')
                        ->get(['id', 'name', 'code']),
                ];
            }
        );
    }

    public function create(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $temporaryPassword = $data['password'] ?? $this->generateTemporaryPassword();
            $data['password'] = Hash::make($temporaryPassword);
            $data['is_active'] = $data['is_active'] ?? true;
            $data['employee_id'] = $data['employee_id'] ?? 'EMP-' . strtoupper(Str::random(8));

            $unitId = $data['unit_id'] ?? null;
            unset($data['unit_id'], $data['role_in_unit']);

            $employee = Employee::create($data);

            if ($unitId) {
                $adminId = auth('admin')?->id();

                EmployeeUnitAssignment::create([
                    'employee_id' => $employee->id,
                    'unit_id' => $unitId,
                    'assigned_by_admin_id' => $adminId,
                    'status' => 'assigned',
                    'priority' => 'medium',
                    'assigned_at' => now(),
                ]);
            }

            try {
                $unit = $unitId ? Unit::find($unitId) : null;
                Mail::to($employee->email)->send(new WelcomeMail(
                    $employee->id,
                    $employee->name,
                    $employee->email,
                    $temporaryPassword,
                    $unit ? [$unit->name] : []
                ));
            } catch (\Exception $e) {
                Log::warning("Failed to send welcome email to {$employee->email}: " . $e->getMessage());
            }

            $this->invalidateEmployeeCache($employee->id);

            return $employee->fresh(['employeeAssignments.unit']);
        });
    }

    protected function generateTemporaryPassword(): string
    {
        $letters = strtoupper(Str::random(4));
        $numbers = random_int(1000, 9999);
        return $letters . $numbers;
    }

    public function getDetail(int $id): array
    {
        $cacheKey = "admin_employee_detail_{$id}";

        return Cache::tags(['employees', "employee_{$id}"])->remember(
            $cacheKey,
            300,
            function () use ($id) {
                $employee = Employee::with(['employeeAssignments.unit'])->findOrFail($id);

                return [
                    'employee' => $employee,
                    'stats' => [
                        'total_units' => $employee->employeeAssignments()
                            ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                            ->whereNull('completed_at')
                            ->count(),
                    ],
                    'active_assignments' => $employee->employeeAssignments()
                        ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                        ->whereNull('completed_at')
                        ->with('unit')
                        ->get(),
                ];
            }
        );
    }

    public function getEditData(int $id): array
    {
        $employee = Employee::with(['employeeAssignments.unit'])->findOrFail($id);
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

            $this->invalidateEmployeeCache($id);

            return $employee->fresh(['employeeAssignments.unit']);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $employee = Employee::findOrFail($id);

            $employee->employeeAssignments()
                ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                ->update([
                    'status' => 'cancelled',
                    'completed_at' => now(),
                ]);

            $employee->delete();

            $this->invalidateEmployeeCache($id);

            return true;
        });
    }

    public function restore(int $id): Employee
    {
        return DB::transaction(function () use ($id) {
            $employee = Employee::onlyTrashed()->findOrFail($id);
            $employee->restore();

            $this->invalidateEmployeeCache($id);

            return $employee;
        });
    }

    public function assignToUnit(int $employeeId, int $unitId): EmployeeUnitAssignment
    {
        return DB::transaction(function () use ($employeeId, $unitId) {
            Employee::findOrFail($employeeId);
            Unit::findOrFail($unitId);

            $adminId = auth('admin')?->id();

            if (!$adminId) {
                throw new \Exception('Authenticated admin is required to create assignments.');
            }

            $existing = EmployeeUnitAssignment::where('employee_id', $employeeId)
                ->where('unit_id', $unitId)
                ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                ->whereNull('completed_at')
                ->first();

            if ($existing) {
                throw new \Exception('Karyawan sudah ditugaskan ke unit ini.');
            }

            $assignment = EmployeeUnitAssignment::create([
                'employee_id' => $employeeId,
                'unit_id' => $unitId,
                'assigned_by_admin_id' => $adminId,
                'status' => 'assigned',
                'priority' => 'medium',
                'assigned_at' => now(),
            ]);

            $this->invalidateEmployeeCache($employeeId);

            return $assignment->load('unit');
        });
    }

    public function removeFromUnit(int $employeeId, int $unitId): bool
    {
        return DB::transaction(function () use ($employeeId, $unitId) {
            $assignment = EmployeeUnitAssignment::where('employee_id', $employeeId)
                ->where('unit_id', $unitId)
                ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                ->whereNull('completed_at')
                ->first();

            if (!$assignment) {
                throw new \Exception('Karyawan tidak ditugaskan ke unit ini.');
            }

            $assignment->update([
                'status' => 'cancelled',
                'completed_at' => now(),
            ]);

            $this->invalidateEmployeeCache($employeeId);

            return true;
        });
    }

    public function getAssignedUnits(int $employeeId): array
    {
        $cacheKey = "admin_employee_assigned_units_{$employeeId}";

        return Cache::tags(['employees', "employee_{$employeeId}"])->remember(
            $cacheKey,
            300,
            function () use ($employeeId) {
                return Employee::findOrFail($employeeId)
                    ->employeeAssignments()
                    ->whereIn('status', ['assigned', 'accepted', 'in_progress', 'waiting_verification'])
                    ->whereNull('completed_at')
                    ->with('unit')
                    ->get()
                    ->toArray();
            }
        );
    }

    private function invalidateEmployeeCache(int $employeeId): void
    {
        try {
            Cache::tags(['employees', "employee_{$employeeId}", 'dashboard'])->flush();
        } catch (\BadMethodCallException $e) {
            Cache::forget("admin_employee_detail_{$employeeId}");
            Cache::forget("admin_employee_assigned_units_{$employeeId}");
            Cache::forget('employee_filter_units');
            Cache::forget('employee_form_data');
        }
    }
}