<?php

namespace App\Observers\Admin;

use App\Models\Report;
use App\Models\ModerationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportObserver
{
    public function created(Report $report): void
    {
        $this->logActivity('created', $report);
    }

    public function updated(Report $report): void
    {
        if ($report->isDirty('status')) {
            $this->logActivity('status_changed', $report, [
                'old' => $report->getOriginal('status'),
                'new' => $report->status,
            ]);
        }

        if ($report->isDirty('priority')) {
            $this->logActivity('priority_changed', $report, [
                'old' => $report->getOriginal('priority'),
                'new' => $report->priority,
            ]);
        }
    }

    public function deleted(Report $report): void
    {
        $this->logActivity('deleted', $report);
    }

    protected function logActivity(string $action, Report $report, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'report_' . $action,
                'target_type' => 'report',
                'target_id' => $report->id,
                'metadata' => json_encode(array_merge([
                    'tracking_code' => $report->tracking_code,
                    'unit_id' => $report->unit_id,
                    'rating_id' => $report->rating_id,
                    'priority' => $report->priority,
                    'status' => $report->status,
                ], $additional)),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log report activity: ' . $e->getMessage());
        }
    }
}