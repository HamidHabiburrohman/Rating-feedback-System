<?php

namespace App\Observers\Admin;

use App\Models\Facility;
use App\Models\ModerationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FacilityObserver
{
    public function created(Facility $facility): void
    {
        $this->clearCache();
        $this->logActivity('created', $facility);
    }

    public function updated(Facility $facility): void
    {
        $this->clearCache();
        
        if ($facility->isDirty('name')) {
            $this->logActivity('renamed', $facility, [
                'old' => $facility->getOriginal('name'),
                'new' => $facility->name,
            ]);
        }
        
        if ($facility->isDirty('icon_key')) {
            $this->logActivity('icon_changed', $facility, [
                'old' => $facility->getOriginal('icon_key'),
                'new' => $facility->icon_key,
            ]);
        }
    }

    public function deleted(Facility $facility): void
    {
        $this->clearCache();
        
        if ($facility->isForceDeleting()) {
            $this->logActivity('force_deleted', $facility);
        } else {
            $this->logActivity('deleted', $facility);
        }
    }

    public function restored(Facility $facility): void
    {
        $this->clearCache();
        $this->logActivity('restored', $facility);
    }

    public function forceDeleted(Facility $facility): void
    {
        $this->clearCache();
        $this->logActivity('force_deleted', $facility);
    }

    protected function clearCache(): void
    {
        Cache::forget('facilities.all');
        Cache::forget('facilities.popular');
        Cache::forget('units.filter_data');
    }

    protected function logActivity(string $action, Facility $facility, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'facility_' . $action,
                'target_type' => 'facility',
                'target_id' => $facility->id,
                'metadata' => json_encode(array_merge([
                    'facility_name' => $facility->name,
                    'icon_key' => $facility->icon_key,
                ], $additional)),
            ]);

            Log::info('Facility ' . $action, [
                'facility_id' => $facility->id,
                'facility_name' => $facility->name,
                'admin_id' => Auth::id(),
                'additional' => $additional
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log facility activity: ' . $e->getMessage());
        }
    }
}