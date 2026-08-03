<?php

declare(strict_types=1);

namespace App\Events\Conversation;

use Illuminate\Foundation\Events\Dispatchable;

class MessageSentEvent
{
    use Dispatchable;

    public function __construct(
        public readonly int $messageId,
        public readonly int $conversationId,
        public readonly int $senderId,
    ) {}
}