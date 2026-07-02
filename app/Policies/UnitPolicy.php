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
        return $this->isAdmin($user) || $this->isEmployee($user) || $this->isStudent($user);
    }

    public function view($user, Unit $unit): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        
        if ($this->isAdmin($user)) {
            return true;
        }
        
        if ($this->isEmployee($user)) {
            return $this->isAssignedToUnit($user, $unit->id);
        }
        
        if ($this->isStudent($user)) {
            return true;
        }
        
        return false;
    }

    public function create($user): bool
    {
        return $this->isSuperAdmin($user) || $this->isAdmin($user);
    }

    public function update($user, Unit $unit): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        
        if ($this->isAdmin($user)) {
            return true;
        }
        
        if ($this->isEmployee($user)) {
            return $this->isAssignedToUnit($user, $unit->id);
        }
        
        return false;
    }

    public function delete($user, Unit $unit): bool
    {
        return $this->isSuperAdmin($user) || $this->isAdmin($user);
    }

    public function restore($user, Unit $unit): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function forceDelete($user, Unit $unit): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function managePhotos($user, Unit $unit): bool
    {
        return $this->isSuperAdmin($user) || $this->isAdmin($user);
    }

    public function manageQrCodes($user, Unit $unit): bool
    {
        return $this->isSuperAdmin($user) || $this->isAdmin($user);
    }

    protected function isSuperAdmin($user): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    protected function isAdmin($user): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    protected function isEmployee($user): bool
    {
        return $user instanceof Employee;
    }

    protected function isStudent($user): bool
    {
        return $user instanceof Student;
    }

    protected function isAssignedToUnit($user, int $unitId): bool
    {
        if (!($user instanceof Employee)) {
            return false;
        }

        return $user->unitAssignments()
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->exists();
    }
}