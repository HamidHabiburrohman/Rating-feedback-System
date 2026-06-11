<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService extends BaseEmployeeService
{
    public function getProfile(int $employeeId): array
    {
        $cacheKey = "employee_profile_{$employeeId}";
        
        return Cache::tags(['profile', "employee_{$employeeId}"])->remember($cacheKey, 300, function () use ($employeeId) {
            $employee = Employee::with(['unitAssignments.unit'])->findOrFail($employeeId);
            
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'photo' => $employee->photo,
                'bio' => $employee->bio,
                'is_active' => $employee->is_active,
                'assigned_units' => $employee->unitAssignments
                    ->where('is_active', true)
                    ->map(fn($a) => [
                        'unit_name' => $a->unit->name ?? 'Unknown',
                        'role' => $a->role_in_unit,
                        'started_at' => $a->started_at,
                    ])
                    ->toArray(),
            ];
        });
    }

    public function updateProfile(int $employeeId, array $data): bool
    {
        $employee = Employee::findOrFail($employeeId);
        
        $result = $employee->update($data);
        
        if ($result) {
            Cache::tags(['profile', "employee_{$employeeId}"])->flush();
        }
        
        return $result;
    }

    public function updatePassword(int $employeeId, string $currentPassword, string $newPassword): bool
    {
        $employee = Employee::findOrFail($employeeId);
        
        if (!Hash::check($currentPassword, $employee->password)) {
            throw new \Exception('Password saat ini tidak sesuai');
        }
        
        $result = $employee->update(['password' => Hash::make($newPassword)]);
        
        return $result;
    }

    public function updatePhoto(int $employeeId, $file): string
    {
        $employee = Employee::findOrFail($employeeId);
        
        // Hapus foto lama jika ada
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }
        
        // Upload foto baru
        $path = $file->store('employees/photos', 'public');
        $employee->update(['photo' => $path]);
        
        Cache::tags(['profile', "employee_{$employeeId}"])->flush();
        
        return $path;
    }

    public function removePhoto(int $employeeId): bool
    {
        $employee = Employee::findOrFail($employeeId);
        
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }
        
        $result = $employee->update(['photo' => null]);
        
        if ($result) {
            Cache::tags(['profile', "employee_{$employeeId}"])->flush();
        }
        
        return $result;
    }

    public function getStats(int $employeeId): array
    {
        $cacheKey = "employee_profile_stats_{$employeeId}";
        
        return Cache::tags(['profile', "employee_{$employeeId}"])->remember($cacheKey, 300, function () use ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            
            $assignedUnitIds = $employee->unitAssignments()
                ->where('is_active', true)
                ->pluck('unit_id')
                ->toArray();

            return [
                'total_units' => count($assignedUnitIds),
                'total_rating_replies' => $employee->ratingReplies()->count(),
                'total_report_replies' => $employee->reportReplies()->count(),
            ];
        });
    }
}