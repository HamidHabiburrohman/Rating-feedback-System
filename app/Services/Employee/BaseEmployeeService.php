<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use Illuminate\Support\Facades\Auth;

abstract class BaseEmployeeService
{
    protected function getEmployeeId(): ?int
    {
        return Auth::guard('employee')->id();
    }

    protected function getEmployee(): ?Employee
    {
        return Auth::guard('employee')->user();
    }

    protected function ensureEmployeeAuthenticated(): void
    {
        if (!$this->getEmployeeId()) {
            throw new \Exception('Sesi employee tidak ditemukan atau sudah kadaluarsa.');
        }
    }

    protected function isAssignedToUnit(int $unitId): bool
    {
        $employee = $this->getEmployee();
        if (!$employee) return false;

        return $employee->unitAssignments()
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
            })
            ->exists();
    }
}