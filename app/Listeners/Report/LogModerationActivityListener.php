<?php

    namespace App\Listeners\Report;

    use App\Events\Auth\AccountDeactivatedEvent;
    use App\Events\Rating\RatingStatusUpdatedEvent;
    use App\Events\Report\ReportStatusUpdatedEvent;
    use App\Models\System\ModerationLog;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Support\Facades\Log;

    class LogModerationActivityListener implements ShouldQueue
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
                if ($event instanceof ReportStatusUpdatedEvent) {
                    ModerationLog::create([
                        'admin_id' => $event->updatedBy,
                        'action' => 'update_report_status',
                        'target_type' => 'Report',
                        'target_id' => $event->report->id,
                        'details' => json_encode([
                            'old_status' => $event->oldStatus,
                            'new_status' => $event->newStatus,
                            'notes' => $event->notes,
                        ]),
                    ]);
                } elseif ($event instanceof RatingStatusUpdatedEvent) {
                    ModerationLog::create([
                        'admin_id' => $event->moderatedBy,
                        'action' => 'update_rating_status',
                        'target_type' => 'Rating',
                        'target_id' => $event->rating->id,
                        'details' => json_encode([
                            'old_status' => $event->oldStatus,
                            'new_status' => $event->newStatus,
                        ]),
                    ]);
                } elseif ($event instanceof AccountDeactivatedEvent) {
                    ModerationLog::create([
                        'admin_id' => $event->deactivatedBy,
                        'action' => 'deactivate_account',
                        'target_type' => get_class($event->user),
                        'target_id' => $event->user->id,
                        'details' => json_encode([
                            'reason' => $event->reason,
                        ]),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to log moderation activity: ' . $e->getMessage());
            }
        }
    }