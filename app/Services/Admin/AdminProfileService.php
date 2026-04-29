<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class AdminProfileService
{
    public function updateProfile(Admin $admin, array $data): Admin
    {
        $admin->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $admin->phone,
            'position' => $data['position'] ?? $admin->position,
            'bio' => $data['bio'] ?? $admin->bio,
            'location' => $data['location'] ?? $admin->location,
        ]);

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
        Log::info('=== START UPLOAD PHOTO ===');
        Log::info('Admin ID: ' . $admin->id);
        Log::info('Admin Email: ' . $admin->email);
        Log::info('File original name: ' . $photo->getClientOriginalName());
        Log::info('File size: ' . $photo->getSize());
        Log::info('File mime: ' . $photo->getMimeType());
        Log::info('File extension: ' . $photo->getClientOriginalExtension());
        Log::info('File is valid: ' . ($photo->isValid() ? 'YES' : 'NO'));
        Log::info('File error: ' . ($photo->getError() ?: 'None'));

        try {
            // Cek apakah disk public ada
            Log::info('Storage disk public exists: ' . (Storage::disk('public')->exists('.') ? 'YES' : 'NO'));

            // Pastikan folder admins ada
            if (!Storage::disk('public')->exists('admins')) {
                Log::info('Folder admins tidak ada, mencoba membuat...');
                $created = Storage::disk('public')->makeDirectory('admins');
                Log::info('Folder created: ' . ($created ? 'YES' : 'NO'));
            } else {
                Log::info('Folder admins sudah ada');
            }

            // Hapus foto lama
            if ($admin->photo) {
                Log::info('Old photo exists: ' . $admin->photo);
                $deleted = Storage::disk('public')->delete($admin->photo);
                Log::info('Old photo deleted: ' . ($deleted ? 'YES' : 'NO'));
            } else {
                Log::info('No old photo to delete');
            }

            // Simpan foto baru
            $filename = 'admin_' . $admin->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
            Log::info('New filename: ' . $filename);

            $path = $photo->storeAs('admins', $filename, 'public');
            Log::info('StoreAs result path: ' . $path);

            // Cek apakah file benar-benar tersimpan
            $fullPath = storage_path('app/public/' . $path);
            Log::info('Full path: ' . $fullPath);
            Log::info('File exists after store: ' . (file_exists($fullPath) ? 'YES' : 'NO'));
            Log::info('Storage exists after store: ' . (Storage::disk('public')->exists($path) ? 'YES' : 'NO'));

            // Update database
            $admin->update(['photo' => $path]);
            Log::info('Database updated with path: ' . $path);

            // Cek setelah update
            $fresh = $admin->fresh();
            Log::info('After update - photo field: ' . $fresh->photo);
            Log::info('After update - photo_url: ' . $fresh->photo_url);

            Log::info('=== UPLOAD PHOTO SUCCESS ===');
        } catch (\Exception $e) {
            Log::error('=== UPLOAD PHOTO FAILED ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error file: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Error trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function removePhoto(Admin $admin): void
    {
        Log::info('=== REMOVE PHOTO ===');
        Log::info('Admin ID: ' . $admin->id);
        Log::info('Current photo: ' . $admin->photo);

        if ($admin->photo) {
            $deleted = Storage::disk('public')->delete($admin->photo);
            Log::info('Photo deleted from storage: ' . ($deleted ? 'YES' : 'NO'));
        }

        $admin->update(['photo' => null]);
        Log::info('Database updated: photo set to null');
        Log::info('=== REMOVE PHOTO SUCCESS ===');
    }
}
