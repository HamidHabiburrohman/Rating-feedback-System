<?php

namespace App\Observers;

use App\Models\Report\Report;
use App\Models\Employee\EmployeeUnitAssignment;
use Illuminate\Support\Facades\Cache;

class ReportObserver
{
    public function created(Report $report): void
    {
        $this->autoAssignToEmployee($report);
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

    protected function autoAssignToEmployee(Report $report): void
    {
        if ($report->status !== 'new') {
            return;
        }

        $activeStatuses = ['assigned', 'accepted', 'in_progress', 'waiting_verification'];

        $assignment = EmployeeUnitAssignment::where('unit_id', $report->unit_id)
            ->whereIn('status', $activeStatuses)  // GANTI DARI where('is_active', true)
            ->inRandomOrder()
            ->first();

        if ($assignment) {
            EmployeeUnitAssignment::create([
                'employee_id' => $assignment->employee_id,
                'unit_id' => $report->unit_id,
                'report_id' => $report->id,
                'assigned_by_admin_id' => $assignment->assigned_by_admin_id,
                'status' => 'assigned',
                'priority' => $report->priority,
                'notes' => 'Auto-assigned from report creation',
                'assigned_at' => now(),
            ]);

            $report->update(['status' => 'assigned']);
        }
    }

    protected function clearCache(Report $report): void
    {
        Cache::tags(['reports', "unit_{$report->unit_id}", 'dashboard'])->flush();
    }
}