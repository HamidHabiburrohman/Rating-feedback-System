<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\Unit\Unit;

class UnitPolicy
{
    public function before($user, string $ability): bool|null
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return null;
    }

    
    public function viewAny($user): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            return true;
        }

        return false;
    }
    public function view($user, Unit $unit): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            if (isset($unit->unit_id)) {
                return $this->isAssignedToUnit($user, $unit->unit_id);
            }
            return false;
        }

        if ($this->isStudent($user)) {
            if ($unit instanceof \App\Models\Feedback\Rating) {
                return $unit->student_id === $user->id;
            }
            if ($unit instanceof \App\Models\Reports\Report) {
                return $unit->student_id === $user->id;
            }
            return true;
        }

        return false;
    }
    public function create($user): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            return true;
        }

        return false;
    }
    public function update($user, Unit $unit): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($unit instanceof \App\Models\Feedback\Rating) {
                return $unit->student_id === $user->id && $unit->status !== 'archived';
            }
            if ($unit instanceof \App\Models\Reports\Report) {
                return $unit->student_id === $user->id &&
                       in_array($unit->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function delete($user, Unit $unit): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($unit instanceof \App\Models\Feedback\Rating) {
                return $unit->student_id === $user->id && $unit->status !== 'archived';
            }
            if ($unit instanceof \App\Models\Reports\Report) {
                return $unit->student_id === $user->id &&
                       in_array($unit->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function restore($user, Unit $unit): bool
    {
        return $this->isSuperAdmin($user);
    }
    public function managePhotos($user): bool
    {
        return $this->isAdmin($user);
    }
    public function manageQrCodes($user): bool
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
            ->where(function($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
            })
            ->exists();
    }
}