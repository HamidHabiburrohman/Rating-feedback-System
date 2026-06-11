<?php

namespace App\Observers;

use App\Models\Unit\Facility;
use Illuminate\Support\Facades\Cache;

class FacilityObserver
{
    public function created(Facility $facility): void
    {
        $this->clearCache();
    }

    public function updated(Facility $facility): void
    {
        if ($facility->isDirty(['is_active', 'name', 'icon'])) {
            $this->clearCache();
        }
    }

    public function deleted(Facility $facility): void
    {
        $this->clearCache();
    }

    public function restored(Facility $facility): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::tags(['facilities', 'dropdown'])->flush();
        Cache::tags(['landing'])->flush();
    }
}