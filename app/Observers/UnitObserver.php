<?php

namespace App\Observers;

use App\Models\Unit\Unit;
use Illuminate\Support\Facades\Cache;

class UnitObserver
{
    public function created(Unit $unit): void
    {
        $this->clearCache($unit);
    }

    public function updated(Unit $unit): void
    {
        if ($unit->isDirty(['is_active', 'name', 'slug', 'unit_type_id', 'unit_department_id', 'avg_rating', 'total_ratings'])) {
            $this->clearCache($unit);
        }
    }

    public function deleted(Unit $unit): void
    {
        $this->clearCache($unit);
    }

    public function restored(Unit $unit): void
    {
        $this->clearCache($unit);
    }

    protected function clearCache(Unit $unit): void
    {
        Cache::tags(['units'])->flush();
        Cache::tags(['landing'])->flush();
        Cache::tags(['dashboard'])->flush();
    }
}