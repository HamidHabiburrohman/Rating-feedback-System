<?php

declare(strict_types=1);

namespace App\Events\Report;

use Illuminate\Foundation\Events\Dispatchable;

class ReportStatusUpdatedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly int $reportId,
        public readonly string $status,
    ) {}
}