<?php

namespace App\Listeners\Conversation;

use App\Events\Conversation\MessageSentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BroadcastNewMessageListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param MessageSentEvent $event
     * @return void
     */
    public function handle(MessageSentEvent $event): void
    {
        Log::info("Message ID {$event->message->id} broadcasted on conversation {$event->message->conversation_id}");
    }
}