<?php

namespace App\Events\Rating;

use App\Models\Feedback\RatingReply;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingRepliedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param RatingReply $reply
     */
    public function __construct(public RatingReply $reply) {}
}