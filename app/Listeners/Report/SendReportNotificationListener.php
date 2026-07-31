<?php

namespace App\Listeners\Report;

use App\Events\Report\ReportRepliedEvent;
use App\Events\Report\ReportStatusUpdatedEvent;
use App\Events\Report\ReportSubmittedEvent;
use App\Notifications\Report\ReportRepliedNotification;
use App\Notifications\Report\ReportStatusChangedNotification;
use App\Notifications\Report\ReportSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendReportNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {
        try {
            if ($event instanceof ReportSubmittedEvent) {
                $report = $event->report;
                $unit = $report->unit;

                if ($unit && $unit->employees) {
                    foreach ($unit->employees as $employee) {
                        $employee->notify(new ReportSubmittedNotification($report));
                    }
                }
            } elseif ($event instanceof ReportStatusUpdatedEvent) {
                $report = $event->report;
                $student = $report->student;

                if ($student) {
                    $student->notify(new ReportStatusChangedNotification($report, $event->oldStatus, $event->newStatus));
                }
            } elseif ($event instanceof ReportRepliedEvent) {
                $reply = $event->reply;
                $student = $reply->report?->student;

                if ($student) {
                    $student->notify(new ReportRepliedNotification($reply));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send report notification: ' . $e->getMessage());
        }
    }
}