<?php

declare(strict_types=1);

namespace App\Listeners\Conversation;

use App\Events\Conversation\MessageSentEvent;

final class BroadcastNewMessageListener
{
    /**
     * Handle the event.
     *
     * This listener serves as a placeholder for future realtime broadcasting
     * (e.g., Laravel Reverb / WebSocket). 
     * 
     * Once the broadcasting infrastructure is implemented, this Listener will
     * be responsible for pushing the new message to the conversation workspace.
     */
    public function handle(MessageSentEvent $event): void
    {
        // Future: Broadcast message to conversation participants via WebSocket.
    }
}