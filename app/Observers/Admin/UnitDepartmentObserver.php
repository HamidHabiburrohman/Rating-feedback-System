<?php

namespace App\Observers\Admin;

use App\Models\ModerationLog;
use App\Models\UnitDepartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnitDepartmentObserver
{
    public function created(UnitDepartment $department): void
    {
        $this->logActivity('created', $department);
    }

    public function updated(UnitDepartment $department): void
    {
        if ($department->isDirty('is_active')) {
            $this->logActivity('status_changed', $department, [
                'old' => $department->getOriginal('is_active'),
                'new' => $department->is_active
            ]);
        }
    }

    public function deleted(UnitDepartment $department): void
    {
        if ($department->isForceDeleting()) {
            $this->logActivity('force_deleted', $department);
        } else {
            $this->logActivity('soft_deleted', $department);
        }
    }

    public function restored(UnitDepartment $department): void
    {
        $this->logActivity('restored', $department);
    }

    public function forceDeleted(UnitDepartment $department): void
    {
        $this->logActivity('force_deleted', $department);
    }

    public function saving(UnitDepartment $department): void
    {
        if (empty($department->slug) && !empty($department->name)) {
            $department->slug = Str::slug($department->name);
        }
    }

    public function saved(UnitDepartment $department): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::forget('unit_departments.active');
        Cache::forget('unit_departments.list');
        Cache::forget('units.filter_data');
    }

    protected function logActivity(string $action, UnitDepartment $department, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'unit_department_' . $action,
                'target_type' => 'unit_department',
                'target_id' => $department->id,
                'metadata' => json_encode(array_merge([
                    'department_name' => $department->name,
                    'department_code' => $department->code,
                ], $additional)),
            ]);

            Log::info('UnitDepartment ' . $action, [
                'department_id' => $department->id,
                'department_name' => $department->name,
                'admin_id' => Auth::id(),
                'additional' => $additional
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log department activity: ' . $e->getMessage());
        }
    }
}