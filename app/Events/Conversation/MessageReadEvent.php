<?php

namespace App\Events\Conversation;

use App\Models\Conversation\MessageRead;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReadEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param MessageRead $messageRead
     */
    public function __construct(public MessageRead $messageRead) {}
}