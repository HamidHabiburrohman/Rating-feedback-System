<?php

declare(strict_types=1);

namespace App\Services\Admin\Conversation;

use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use App\Models\Conversation\MessageRead;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class MessageReadService
{
    public function markAsRead(Message $message, Authenticatable $reader): MessageRead
    {
        return MessageRead::updateOrCreate(
            [
                'message_id' => $message->id,
                'reader_type' => get_class($reader),
                'reader_id' => $reader->getAuthIdentifier(),
            ],
            [
                'read_at' => now(),
            ]
        );
    }

    public function markAllAsRead(Conversation $conversation, Authenticatable $reader): int
    {
        $unreadMessages = $conversation->messages()
            ->whereDoesntHave('reads', function ($query) use ($reader) {
                $query->where('reader_type', get_class($reader))
                      ->where('reader_id', $reader->getAuthIdentifier());
            })
            ->where(function ($query) use ($reader) {
                $query->where('sender_type', '!=', get_class($reader))
                      ->orWhere('sender_id', '!=', $reader->getAuthIdentifier());
            })
            ->get();

        $count = 0;
        
        DB::transaction(function () use ($unreadMessages, $reader, &$count) {
            foreach ($unreadMessages as $message) {
                MessageRead::create([
                    'message_id' => $message->id,
                    'reader_type' => get_class($reader),
                    'reader_id' => $reader->getAuthIdentifier(),
                    'read_at' => now(),
                ]);
                $count++;
            }
        });

        return $count;
    }

    public function unreadCount(Conversation $conversation, Authenticatable $reader): int
    {
        return $conversation->messages()
            ->whereDoesntHave('reads', function ($query) use ($reader) {
                $query->where('reader_type', get_class($reader))
                      ->where('reader_id', $reader->getAuthIdentifier());
            })
            ->where(function ($query) use ($reader) {
                $query->where('sender_type', '!=', get_class($reader))
                      ->orWhere('sender_id', '!=', $reader->getAuthIdentifier());
            })
            ->count();
    }

    public function isRead(Message $message, Authenticatable $reader): bool
    {
        return $message->reads()
            ->where('reader_type', get_class($reader))
            ->where('reader_id', $reader->getAuthIdentifier())
            ->exists();
    }

    public function lastRead(Conversation $conversation, Authenticatable $reader): ?Message
    {
        $lastReadRecord = MessageRead::where('reader_type', get_class($reader))
            ->where('reader_id', $reader->getAuthIdentifier())
            ->whereHas('message', fn($q) => $q->where('conversation_id', $conversation->id))
            ->latest('read_at')
            ->first();

        return $lastReadRecord?->message;
    }

    public function getReadHistory(Message $message): Collection
    {
        return $message->reads()
            ->with('reader')
            ->latest('read_at')
            ->get();
    }

    public function bulkRead(array $messageIds, Authenticatable $reader): int
    {
        $count = 0;
        
        DB::transaction(function () use ($messageIds, $reader, &$count) {
            foreach ($messageIds as $messageId) {
                MessageRead::updateOrCreate(
                    [
                        'message_id' => $messageId,
                        'reader_type' => get_class($reader),
                        'reader_id' => $reader->getAuthIdentifier(),
                    ],
                    [
                        'read_at' => now(),
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    public function getStatistics(Conversation $conversation): array
    {
        $totalMessages = $conversation->messages()->count();
        $totalParticipants = $conversation->participants()->whereNull('left_at')->count();
        $totalReads = MessageRead::whereHas('message', fn($q) => $q->where('conversation_id', $conversation->id))->count();

        $maxPossibleReads = $totalMessages * $totalParticipants;

        return [
            'total_messages' => $totalMessages,
            'total_reads' => $totalReads,
            'read_rate' => $maxPossibleReads > 0 ? round(($totalReads / $maxPossibleReads) * 100, 2) : 0
    ];
    }
}