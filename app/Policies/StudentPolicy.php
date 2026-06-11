<?php

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;

class StudentPolicy
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
        if ($user instanceof Admin) return in_array($user->role, ['super_admin', 'admin']);
        if ($user instanceof Employee) return true;
        return false;
    }

    public function view($user, Student $student): bool
    {
        if ($user instanceof Admin) return true;
        if ($user instanceof Employee) return true;
        if ($user instanceof Student) return $user->id === $student->id;
        return false;
    }

    public function create($user): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    public function update($user, Student $student): bool
    {
        if ($user instanceof Admin) return true;
        if ($user instanceof Student) return $user->id === $student->id;
        return false;
    }

    public function delete($user, Student $student): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    public function restore($user, Student $student): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    public function forceDelete($user, Student $student): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    public function viewDashboard($user, Student $student): bool
    {
        return $user instanceof Student && $user->id === $student->id;
    }
}