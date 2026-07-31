<?php

namespace App\Listeners\Conversation;

use App\Events\Conversation\MessageSentEvent;
use App\Notifications\Conversation\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendUnreadMessageNotificationListener implements ShouldQueue
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
        try {
            $message = $event->message;
            $conversation = $message->conversation;

            if (!$conversation) {
                return;
            }

            $participants = $conversation->participants()
                ->where('user_id', '!=', $message->sender_id)
                ->get();

            foreach ($participants as $participant) {
                if ($participant->user && method_exists($participant->user, 'notify')) {
                    $participant->user->notify(new NewMessageNotification($message));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send unread message notification: ' . $e->getMessage(), [
                'message_id' => $event->message->id,
            ]);
        }
    }
}