<?php

declare(strict_types=1);

namespace App\Events\Auth;

use Illuminate\Foundation\Events\Dispatchable;

class StudentRegisteredEvent
{
    use Dispatchable;

    public function __construct(
        public readonly int $studentId,
        public readonly string $name,
        public readonly string $email,
    ) {}
}