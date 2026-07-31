<?php

namespace App\Events\Rating;

use App\Models\Feedback\Rating;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingStatusUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Rating $rating
     * @param string $oldStatus
     * @param string $newStatus
     * @param int|null $moderatedBy
     */
    public function __construct(
        public Rating $rating,
        public string $oldStatus,
        public string $newStatus,
        public ?int $moderatedBy = null
    ) {}
}