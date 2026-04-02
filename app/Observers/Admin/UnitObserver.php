<?php

namespace App\Observers\Admin;

use App\Models\ModerationLog;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitDepartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UnitObserver
{
    public function created(Unit $unit): void
    {
        $this->updateUnitTypeCounter($unit->unit_type_id);
        $this->updateUnitDepartmentCounter($unit->unit_department_id);
        $this->logActivity('created', $unit);
    }

    public function updated(Unit $unit): void
    {
        if ($unit->isDirty('unit_type_id')) {
            $this->updateUnitTypeCounter($unit->unit_type_id);
            $this->updateUnitTypeCounter($unit->getOriginal('unit_type_id'));
        }
        
        if ($unit->isDirty('unit_department_id')) {
            $this->updateUnitDepartmentCounter($unit->unit_department_id);
            $this->updateUnitDepartmentCounter($unit->getOriginal('unit_department_id'));
        }
        
        if ($unit->isDirty('is_active')) {
            $this->logActivity('status_changed', $unit, [
                'old' => $unit->getOriginal('is_active'),
                'new' => $unit->is_active
            ]);
        }
        
        if ($unit->isDirty('operational_status')) {
            $this->logActivity('operational_status_changed', $unit, [
                'old' => $unit->getOriginal('operational_status'),
                'new' => $unit->operational_status
            ]);
        }
    }

    public function deleted(Unit $unit): void
    {
        $this->updateUnitTypeCounter($unit->unit_type_id);
        $this->updateUnitDepartmentCounter($unit->unit_department_id);
        
        if ($unit->isForceDeleting()) {
            $this->logActivity('force_deleted', $unit);
        } else {
            $this->logActivity('soft_deleted', $unit);
        }
    }

    public function restored(Unit $unit): void
    {
        $this->updateUnitTypeCounter($unit->unit_type_id);
        $this->updateUnitDepartmentCounter($unit->unit_department_id);
        $this->logActivity('restored', $unit);
    }

    public function forceDeleted(Unit $unit): void
    {
        $this->updateUnitTypeCounter($unit->unit_type_id);
        $this->updateUnitDepartmentCounter($unit->unit_department_id);
        $this->logActivity('force_deleted', $unit);
    }

    public function saving(Unit $unit): void
    {
        if (empty($unit->slug) && !empty($unit->name)) {
            $unit->slug = str()->slug($unit->name);
        }
    }

    public function saved(Unit $unit): void
    {
        $this->clearCache();
    }

    protected function updateUnitTypeCounter(?int $unitTypeId): void
    {
        if (!$unitTypeId) {
            return;
        }
        
        $count = Unit::where('unit_type_id', $unitTypeId)->count();
        
        UnitType::where('id', $unitTypeId)->update(['units_count' => $count]);
    }

    protected function updateUnitDepartmentCounter(?int $unitDepartmentId): void
    {
        if (!$unitDepartmentId) {
            return;
        }
        
        $count = Unit::where('unit_department_id', $unitDepartmentId)->count();
        
        UnitDepartment::where('id', $unitDepartmentId)->update(['units_count' => $count]);
    }

    protected function clearCache(): void
    {
        Cache::forget('units.list');
        Cache::forget('units.stats');
        Cache::forget('units.filter_data');
        Cache::forget('dashboard.stats.units');
    }

    protected function logActivity(string $action, Unit $unit, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'unit_' . $action,
                'target_type' => 'unit',
                'target_id' => $unit->id,
                'metadata' => json_encode(array_merge([
                    'unit_name' => $unit->name,
                    'unit_code' => $unit->code,
                ], $additional)),
            ]);

            Log::info('Unit ' . $action, [
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'admin_id' => Auth::id(),
                'additional' => $additional
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log unit activity: ' . $e->getMessage());
        }
    }
}