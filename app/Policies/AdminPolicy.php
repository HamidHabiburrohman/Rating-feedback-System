<?php

namespace App\Policies;

use App\Models\Authentication\Admin;

class AdminPolicy
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
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }

    public function view($user, Admin $admin): bool
    {
        if (!$user instanceof Admin) return false;
        return $user->id === $admin->id || $user->role === 'super_admin';
    }

    public function create($user): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    public function update($user, Admin $admin): bool
    {
        if (!$user instanceof Admin) return false;
        if ($user->id === $admin->id) return true;
        return $user->role === 'super_admin';
    }

    public function delete($user, Admin $admin): bool
    {
        if (!$user instanceof Admin) return false;
        if ($user->id === $admin->id) return false;
        return $user->role === 'super_admin';
    }

    public function restore($user, Admin $admin): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    public function forceDelete($user, Admin $admin): bool
    {
        return $user instanceof Admin && $user->role === 'super_admin';
    }

    public function updateProfile($user, Admin $admin): bool
    {
        return $user instanceof Admin && $user->id === $admin->id;
    }

    public function viewDashboard($user): bool
    {
        return $user instanceof Admin && in_array($user->role, ['super_admin', 'admin']);
    }
}