<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\AccountDeactivatedEvent;
use App\Models\Authentication\Student;
use App\Notifications\Auth\AccountDeactivatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class SendAccountDeactivatedNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AccountDeactivatedEvent $event): void
    {
        $student = Student::find($event->studentId);

        if (! $student) {
            return;
        }

        $student->notify(new AccountDeactivatedNotification());
    }
}