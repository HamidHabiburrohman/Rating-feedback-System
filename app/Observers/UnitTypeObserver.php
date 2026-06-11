<?php

namespace App\Observers;

use App\Models\Unit\UnitType;
use Illuminate\Support\Facades\Cache;

class UnitTypeObserver
{
    public function created(UnitType $unitType): void
    {
        $this->clearCache();
    }

    public function updated(UnitType $unitType): void
    {
        if ($unitType->isDirty(['is_active', 'name', 'icon', 'sort_order'])) {
            $this->clearCache();
        }
    }

    public function deleted(UnitType $unitType): void
    {
        $this->clearCache();
    }

    public function restored(UnitType $unitType): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::tags(['units', 'dropdown'])->flush();
        Cache::tags(['landing'])->flush();
    }
}