<?php

namespace App\Services\Admin;

use App\Models\Authentication\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileService extends BaseAdminService
{
    public function getProfile(int $adminId): array
    {
        $admin = Admin::findOrFail($adminId);
        return $admin->toArray();
    }

    public function updateProfile(int $adminId, array $data): bool
    {
        $admin = Admin::findOrFail($adminId);
        return $admin->update($data);
    }

    public function updatePassword(int $adminId, string $currentPassword, string $newPassword): bool
    {
        $admin = Admin::findOrFail($adminId);
        
        if (!Hash::check($currentPassword, $admin->password)) {
            throw new \Exception('Password saat ini tidak sesuai');
        }
        
        return $admin->update(['password' => Hash::make($newPassword)]);
    }

    public function updatePhoto(int $adminId, $file): string
    {
        $admin = Admin::findOrFail($adminId);
        
        if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
            Storage::disk('public')->delete($admin->photo);
        }
        
        $path = $file->store('admin/photos', 'public');
        $admin->update(['photo' => $path]);
        
        return $path;
    }

    public function removePhoto(int $adminId): bool
    {
        $admin = Admin::findOrFail($adminId);
        
        if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
            Storage::disk('public')->delete($admin->photo);
        }
        
        return $admin->update(['photo' => null]);
    }
}