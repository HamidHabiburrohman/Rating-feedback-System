<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\Unit\QrCode;

class QrCodePolicy
{
    public function before($user, string $ability): ?bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return null;
    }

    public function viewAny($user): bool
    {
        return $this->isAdmin($user);
    }

    public function view($user, QrCode $qrCode): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            return $this->isAssignedToUnit($user, $qrCode->unit_id);
        }

        return false;
    }

    public function create($user): bool
    {
        return $this->isAdmin($user);
    }

    public function update($user, QrCode $qrCode): bool
    {
        return $this->isAdmin($user);
    }

    public function delete($user, QrCode $qrCode): bool
    {
        return $this->isAdmin($user);
    }

    protected function isAdmin($user): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    protected function isSuperAdmin($user): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    protected function isEmployee($user): bool
    {
        return $user instanceof Employee;
    }

    protected function isStudent($user): bool
    {
        return $user instanceof Student;
    }

    protected function isAssignedToUnit(Employee $employee, int $unitId): bool
    {
        return $employee->unitAssignments()
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
            })
            ->exists();
    }
}