<?php

namespace App\Observers;

use App\Models\Report\Report;
use Illuminate\Support\Facades\Cache;

class ReportObserver
{
    public function created(Report $report): void
    {
        $this->clearCache($report);
    }

    public function updated(Report $report): void
    {
        if ($report->isDirty(['status', 'priority'])) {
            $this->clearCache($report);
        }
    }

    public function deleted(Report $report): void
    {
        $this->clearCache($report);
    }

    public function restored(Report $report): void
    {
        $this->clearCache($report);
    }

    protected function clearCache(Report $report): void
    {
        Cache::tags(['reports'])->flush();
        Cache::tags(['dashboard'])->flush();
        Cache::tags(["student_{$report->student_id}"])->flush();
        
        if ($report->unit_id) {
            $employeeIds = \App\Models\Employee\EmployeeUnitAssignment::where('unit_id', $report->unit_id)
                ->where('is_active', true)
                ->pluck('employee_id');
            
            foreach ($employeeIds as $employeeId) {
                Cache::tags(["employee_{$employeeId}"])->flush();
            }
        }
    }
}