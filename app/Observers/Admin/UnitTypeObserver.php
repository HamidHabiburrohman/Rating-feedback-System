<?php

namespace App\Observers\Admin;

use App\Models\ModerationLog;
use App\Models\UnitType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnitTypeObserver
{
    public function created(UnitType $unitType): void
    {
        $this->logActivity('created', $unitType);
    }

    public function updated(UnitType $unitType): void
    {
        if ($unitType->isDirty('is_active')) {
            $this->logActivity('status_changed', $unitType, [
                'old' => $unitType->getOriginal('is_active'),
                'new' => $unitType->is_active
            ]);
        }
        
        if ($unitType->isDirty('icon_key')) {
            $this->logActivity('icon_changed', $unitType, [
                'old' => $unitType->getOriginal('icon_key'),
                'new' => $unitType->icon_key
            ]);
        }
    }

    public function deleted(UnitType $unitType): void
    {
        if ($unitType->isForceDeleting()) {
            $this->logActivity('force_deleted', $unitType);
        } else {
            $this->logActivity('soft_deleted', $unitType);
        }
    }

    public function restored(UnitType $unitType): void
    {
        $this->logActivity('restored', $unitType);
    }

    public function forceDeleted(UnitType $unitType): void
    {
        $this->logActivity('force_deleted', $unitType);
    }

    public function saving(UnitType $unitType): void
    {
        if (empty($unitType->slug) && !empty($unitType->name)) {
            $unitType->slug = Str::slug($unitType->name);
        }
    }

    protected function logActivity(string $action, UnitType $unitType, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'unit_type_' . $action,
                'target_type' => 'unit_type',
                'target_id' => $unitType->id,
                'metadata' => json_encode(array_merge([
                    'unit_type_name' => $unitType->name,
                    'unit_type_slug' => $unitType->slug,
                ], $additional)),
            ]);

            Log::info('UnitType ' . $action, [
                'unit_type_id' => $unitType->id,
                'unit_type_name' => $unitType->name,
                'admin_id' => Auth::id(),
                'additional' => $additional
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log unit type activity: ' . $e->getMessage());
        }
    }
}