<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\System\Setting;

class SettingPolicy
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
    public function view($user, Setting $setting): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            if (isset($setting->unit_id)) {
                return $this->isAssignedToUnit($user, $setting->unit_id);
            }
            return false;
        }

        if ($this->isStudent($user)) {
            if ($setting instanceof \App\Models\Feedback\Rating) {
                return $setting->student_id === $user->id;
            }
            if ($setting instanceof \App\Models\Reports\Report) {
                return $setting->student_id === $user->id;
            }
            return true;
        }

        return false;
    }
    public function update($user, Setting $setting): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($setting instanceof \App\Models\Feedback\Rating) {
                return $setting->student_id === $user->id && $setting->status !== 'archived';
            }
            if ($setting instanceof \App\Models\Reports\Report) {
                return $setting->student_id === $user->id &&
                       in_array($setting->status, ['new', 'assigned', 'in_progress']);
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