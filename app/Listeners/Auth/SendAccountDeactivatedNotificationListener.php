<?php

namespace App\Listeners\Auth;

use App\Events\Auth\AccountDeactivatedEvent;
use App\Notifications\Auth\AccountDeactivatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendAccountDeactivatedNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param AccountDeactivatedEvent $event
     * @return void
     */
    public function handle(AccountDeactivatedEvent $event): void
    {
        try {
            if (method_exists($event->user, 'notify')) {
                $event->user->notify(new AccountDeactivatedNotification($event->reason));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send account deactivation notification: ' . $e->getMessage(), [
                'user_id' => $event->user->id,
            ]);
        }
    }
}