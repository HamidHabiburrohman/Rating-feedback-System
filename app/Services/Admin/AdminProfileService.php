<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdminProfileService
{
    public function updateProfile(Admin $admin, array $data): Admin
    {
        $updateData = [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $admin->phone,
            'position' => $data['position'] ?? $admin->position,
            'bio' => $data['bio'] ?? $admin->bio,
            'location' => $data['location'] ?? $admin->location,
            'employee_id' => $data['employee_id'] ?? $admin->employee_id,
            'department' => $data['department'] ?? $admin->department,
            'timezone' => $data['timezone'] ?? $admin->timezone,
        ];

        $admin->update($updateData);

        if (isset($data['profile_banner'])) {
            $admin->setPreference('profile_banner', $data['profile_banner']);
        }

        return $admin;
    }

    public function updatePassword(Admin $admin, array $data): void
    {
        $admin->update([
            'password' => Hash::make($data['new_password'])
        ]);
    }

    public function updatePhoto(Admin $admin, UploadedFile $photo): void
    {
        if (!Storage::disk('public')->exists('admins')) {
            Storage::disk('public')->makeDirectory('admins');
        }

        if ($admin->photo) {
            Storage::disk('public')->delete($admin->photo);
        }

        $filename = 'admin_' . $admin->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs('admins', $filename, 'public');

        $admin->update(['photo' => $path]);
    }

    public function removePhoto(Admin $admin): void
    {
        if ($admin->photo) {
            Storage::disk('public')->delete($admin->photo);
        }

        $admin->update(['photo' => null]);
    }

    public function updatePreferences(Admin $admin, array $preferences): Admin
    {
        $admin->mergePreferences($preferences);
        return $admin;
    }
}