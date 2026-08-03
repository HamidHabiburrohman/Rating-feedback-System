<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\StudentRegisteredEvent;
use App\Mail\Student\WelcomeMail;
use App\Models\Authentication\Student;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class SendWelcomeEmailListener implements ShouldQueue
{
    public function handle(StudentRegisteredEvent $event): void
    {
        $student = Student::find($event->studentId);

        if (!$student) {
            return;
        }

        Mail::to($student->email)->send(
            new WelcomeMail(
                studentId: $student->id,
                studentName: $student->name,
                studentIdentifier: $student->student_identifier ?? ''
            )
        );

        $student->notify(new WelcomeNotification());
    }
}