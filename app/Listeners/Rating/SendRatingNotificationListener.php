<?php

namespace App\Listeners\Rating;

use App\Events\Rating\RatingRepliedEvent;
use App\Events\Rating\RatingSubmittedEvent;
use App\Notifications\Rating\RatingRepliedNotification;
use App\Notifications\Rating\RatingSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendRatingNotificationListener implements ShouldQueue
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
            if ($event instanceof RatingSubmittedEvent) {
                $rating = $event->rating;
                $unit = $rating->unit;

                if ($unit && $unit->employees) {
                    foreach ($unit->employees as $employee) {
                        $employee->notify(new RatingSubmittedNotification($rating));
                    }
                }
            } elseif ($event instanceof RatingRepliedEvent) {
                $reply = $event->reply;
                $student = $reply->rating?->student;

                if ($student) {
                    $student->notify(new RatingRepliedNotification($reply));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Failed to handle rating notification listener: ' . $e->getMessage());
        }
    }
}