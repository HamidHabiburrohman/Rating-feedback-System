<?php

namespace App\Services\Admin;

use App\Models\Authentication\Admin;
use Illuminate\Support\Facades\Auth;

abstract class BaseAdminService
{
    protected function getAdminId(): ?int
    {
        return Auth::guard('admin')->id();
    }

    protected function getAdmin(): ?Admin
    {
        return Auth::guard('admin')->user();
    }

    protected function ensureAdminAuthenticated(): void
    {
        if (!$this->getAdminId()) {
            throw new \Exception('Sesi admin tidak ditemukan atau sudah kadaluarsa.');
        }
    }

    protected function isSuperAdmin(): bool
    {
        $admin = $this->getAdmin();
        return $admin && $admin->role === 'super_admin';
    }
}