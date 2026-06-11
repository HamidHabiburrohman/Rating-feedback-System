<?php

namespace App\Observers;

use App\Models\Unit\UnitDepartment;
use Illuminate\Support\Facades\Cache;

class UnitDepartmentObserver
{
    public function created(UnitDepartment $dept): void
    {
        $this->clearCache();
    }

    public function updated(UnitDepartment $dept): void
    {
        if ($dept->isDirty(['is_active', 'name'])) {
            $this->clearCache();
        }
    }

    public function deleted(UnitDepartment $dept): void
    {
        $this->clearCache();
    }

    public function restored(UnitDepartment $dept): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::tags(['units', 'dropdown'])->flush();
        Cache::tags(['landing'])->flush();
    }
}