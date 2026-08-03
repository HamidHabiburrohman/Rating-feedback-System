<?php

declare(strict_types=1);

namespace App\Listeners\Conversation;

use App\Events\Conversation\MessageSentEvent;
use App\Models\Conversation\ConversationParticipant;
use App\Models\Conversation\Message;
use App\Notifications\Conversation\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class SendUnreadMessageNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(MessageSentEvent $event): void
    {
        $message = Message::find($event->messageId);

        if (! $message) {
            return;
        }

        $participants = ConversationParticipant::where('conversation_id', $event->conversationId)
            ->where(function ($query) use ($message) {
                $query->where('participant_type', '!=', $message->sender_type)
                    ->orWhere('participant_id', '!=', $message->sender_id);
            })
            ->with('participant')
            ->get();

        foreach ($participants as $participantRecord) {
            if ($participantRecord->participant) {
                $participantRecord->participant->notify(new NewMessageNotification());
            }
        }
    }
}