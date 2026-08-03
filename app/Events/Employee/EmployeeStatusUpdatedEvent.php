<?php

declare(strict_types=1);

namespace App\Events\Employee;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class EmployeeStatusUpdatedEvent
{
    use Dispatchable;

    public function __construct(
        public int $employeeId,
        public string $previousStatus,
        public string $currentStatus,
    ) {}
}