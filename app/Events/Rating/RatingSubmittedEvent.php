<?php

namespace App\Events\Rating;

use App\Models\Feedback\Rating;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingSubmittedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Rating $rating
     */
    public function __construct(public Rating $rating) {}
}