<?php

namespace App\Listeners\Auth;

use App\Events\Auth\StudentRegisteredEvent;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param StudentRegisteredEvent $event
     * @return void
     */
    public function handle(StudentRegisteredEvent $event): void
    {
        try {
            $event->student->notify(new WelcomeNotification());
        } catch (\Throwable $e) {
            Log::error('Failed to send welcome notification: ' . $e->getMessage(), [
                'student_id' => $event->student->id,
            ]);
        }
    }
}