<?php

namespace App\Listeners\Report;

use App\Events\Report\ReportStatusUpdatedEvent;
use App\Models\Report\ReportStatusHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateReportHistoryLogListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param ReportStatusUpdatedEvent $event
     * @return void
     */
    public function handle(ReportStatusUpdatedEvent $event): void
    {
        try {
            ReportStatusHistory::create([
                'report_id' => $event->report->id,
                'previous_status' => $event->oldStatus,
                'status' => $event->newStatus,
                'notes' => $event->notes,
                'updated_by' => $event->updatedBy,
                'updater_type' => $event->updaterType,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to log report status history: ' . $e->getMessage(), [
                'report_id' => $event->report->id,
            ]);
        }
    }
}