<?php

namespace App\Events\Auth;

use App\Models\Authentication\Student;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Student $student
     */
    public function __construct(public Student $student) {}
}