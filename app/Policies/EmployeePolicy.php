<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;

class EmployeePolicy
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
    public function view($user, Employee $employee): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            if (isset($employee->unit_id)) {
                return $this->isAssignedToUnit($user, $employee->unit_id);
            }
            return false;
        }

        if ($this->isStudent($user)) {
            if ($employee instanceof \App\Models\Feedback\Rating) {
                return $employee->student_id === $user->id;
            }
            if ($employee instanceof \App\Models\Reports\Report) {
                return $employee->student_id === $user->id;
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
    public function update($user, Employee $employee): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($employee instanceof \App\Models\Feedback\Rating) {
                return $employee->student_id === $user->id && $employee->status !== 'archived';
            }
            if ($employee instanceof \App\Models\Reports\Report) {
                return $employee->student_id === $user->id &&
                       in_array($employee->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function delete($user, Employee $employee): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($employee instanceof \App\Models\Feedback\Rating) {
                return $employee->student_id === $user->id && $employee->status !== 'archived';
            }
            if ($employee instanceof \App\Models\Reports\Report) {
                return $employee->student_id === $user->id &&
                       in_array($employee->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function restore($user, Employee $employee): bool
    {
        return $this->isSuperAdmin($user);
    }
    public function assignToUnit($user): bool
    {
        return $this->isAdmin($user);
    }
    public function updateAssignment($user): bool
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