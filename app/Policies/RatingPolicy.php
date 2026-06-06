<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use App\Models\Feedback\Rating;

class RatingPolicy
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
    public function view($user, Rating $rating): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployee($user)) {
            if (isset($rating->unit_id)) {
                return $this->isAssignedToUnit($user, $rating->unit_id);
            }
            return false;
        }

        if ($this->isStudent($user)) {
            if ($rating instanceof \App\Models\Feedback\Rating) {
                return $rating->student_id === $user->id;
            }
            if ($rating instanceof \App\Models\Reports\Report) {
                return $rating->student_id === $user->id;
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
    public function update($user, Rating $rating): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($rating instanceof \App\Models\Feedback\Rating) {
                return $rating->student_id === $user->id && $rating->status !== 'archived';
            }
            if ($rating instanceof \App\Models\Reports\Report) {
                return $rating->student_id === $user->id &&
                       in_array($rating->status, ['new', 'assigned', 'in_progress']);
            }
            return false;
        }

        return false;
    }
    public function delete($user, Rating $rating): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isStudent($user)) {
            if ($rating instanceof \App\Models\Feedback\Rating) {
                return $rating->student_id === $user->id && $rating->status !== 'archived';
            }
            if ($rating instanceof \App\Models\Reports\Report) {
                return $rating->student_id === $user->id &&
                       in_array($rating->status, ['new', 'assigned', 'in_progress']);
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