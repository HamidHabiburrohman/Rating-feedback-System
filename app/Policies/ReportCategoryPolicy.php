<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\Reports\ReportCategory;

class ReportCategoryPolicy
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
    public function view($user, ReportCategory $reportCategory): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            if (isset($reportCategory->unit_id)) {
                return $this->isAssignedToUnit($user, $reportCategory->unit_id);
            }
            return false;
        }

        if ($this->isStudent($user)) {
            if ($reportCategory instanceof \App\Models\Feedback\Rating) {
                return $reportCategory->student_id === $user->id;
            }
            if ($reportCategory instanceof \App\Models\Reports\Report) {
                return $reportCategory->student_id === $user->id;
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
    public function update($user, ReportCategory $reportCategory): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($reportCategory instanceof \App\Models\Feedback\Rating) {
                return $reportCategory->student_id === $user->id && $reportCategory->status !== 'archived';
            }
            if ($reportCategory instanceof \App\Models\Reports\Report) {
                return $reportCategory->student_id === $user->id &&
                       in_array($reportCategory->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function delete($user, ReportCategory $reportCategory): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($reportCategory instanceof \App\Models\Feedback\Rating) {
                return $reportCategory->student_id === $user->id && $reportCategory->status !== 'archived';
            }
            if ($reportCategory instanceof \App\Models\Reports\Report) {
                return $reportCategory->student_id === $user->id &&
                       in_array($reportCategory->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
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