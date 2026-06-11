<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\Unit\UnitPhoto;

class UnitPhotoPolicy
{
    public function before($user, string $ability): bool|null
    {
        if ($user instanceof Admin && $user->role === 'super_admin') {
            return true;
        }
        return null;
    }

    public function viewAny($user): bool
    {
        return $user instanceof Admin || $user instanceof Employee || $user instanceof Student;
    }

    public function view($user, UnitPhoto $photo): bool
    {
        if ($user instanceof Admin) return true;
        if ($user instanceof Employee) return $this->isEmployeeAssignedToUnit($user, $photo->unit_id);
        if ($user instanceof Student) return true;
        return false;
    }

    public function create($user): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    public function update($user, UnitPhoto $photo): bool
    {
        if ($user instanceof Admin) return in_array($user->role, ['super_admin', 'admin']);
        if ($user instanceof Employee) return $this->isEmployeeAssignedToUnit($user, $photo->unit_id);
        return false;
    }

    public function delete($user, UnitPhoto $photo): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    public function setPrimary($user, UnitPhoto $photo): bool
    {
        return $this->update($user, $photo);
    }

    public function reorder($user, UnitPhoto $photo): bool
    {
        return $this->update($user, $photo);
    }

    protected function isEmployeeAssignedToUnit(Employee $employee, int $unitId): bool
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