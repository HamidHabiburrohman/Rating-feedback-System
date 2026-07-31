<?php

namespace App\Events\Report;

use App\Models\Report\Report;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportStatusUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Report $report
     * @param string $oldStatus
     * @param string $newStatus
     * @param string|null $notes
     * @param int|null $updatedBy
     * @param string|null $updaterType
     */
    public function __construct(
        public Report $report,
        public string $oldStatus,
        public string $newStatus,
        public ?string $notes = null,
        public ?int $updatedBy = null,
        public ?string $updaterType = null
    ) {}
}