<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\Reports\Report;

class ReportPolicy
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
    public function view($user, Report $report): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            if (isset($report->unit_id)) {
                return $this->isAssignedToUnit($user, $report->unit_id);
            }
            return false;
        }

        if ($this->isStudent($user)) {
            if ($report instanceof \App\Models\Feedback\Rating) {
                return $report->student_id === $user->id;
            }
            if ($report instanceof \App\Models\Reports\Report) {
                return $report->student_id === $user->id;
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
    public function update($user, Report $report): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($report instanceof \App\Models\Feedback\Rating) {
                return $report->student_id === $user->id && $report->status !== 'archived';
            }
            if ($report instanceof \App\Models\Reports\Report) {
                return $report->student_id === $user->id &&
                       in_array($report->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function delete($user, Report $report): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($report instanceof \App\Models\Feedback\Rating) {
                return $report->student_id === $user->id && $report->status !== 'archived';
            }
            if ($report instanceof \App\Models\Reports\Report) {
                return $report->student_id === $user->id &&
                       in_array($report->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function reply($user, $rating): bool
    {
        if ($this->isEmployee($user) && isset($rating->unit_id)) {
            return $this->isAssignedToUnit($user, $rating->unit_id);
        }

        return false;
    }
    public function editReply($user, $reply): bool
    {
        if ($this->isEmployee($user)) {
            return $reply->employee_id === $user->id;
        }

        return false;
    }
    public function deleteReply($user, $reply): bool
    {
        if ($this->isEmployee($user)) {
            return $reply->employee_id === $user->id;
        }

        return false;
    }
    public function updateStatus($user, $report): bool
    {
        if ($this->isEmployee($user) && isset($report->unit_id)) {
            return $this->isAssignedToUnit($user, $report->unit_id);
        }

        return false;
    }
    public function resolve($user, $report): bool
    {
        if ($this->isEmployee($user) && isset($report->unit_id)) {
            return $this->isAssignedToUnit($user, $report->unit_id);
        }

        return false;
    }
    public function reopen($user, $report): bool
    {
        if ($this->isEmployee($user) && isset($report->unit_id)) {
            return $this->isAssignedToUnit($user, $report->unit_id);
        }

        return false;
    }
    public function assign($user, $report): bool
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