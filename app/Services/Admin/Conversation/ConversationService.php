<?php
declare(strict_types=1);

namespace App\Services\Admin\Conversation;

use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Authentication\Admin;

final class ConversationService
{
    public function __construct(
        private readonly ConversationParticipantService $participantService
    ) {}

    public function find(int $id): ?Conversation
    {
        return Conversation::with(['participants.participant', 'latestMessage.sender'])->find($id);
    }

    public function findOrCreate(string $subject, array $participants): Conversation
    {
        return DB::transaction(function () use ($subject, $participants) {
            $conversation = Conversation::create([
                'subject' => $subject,
                'status' => 'active',
            ]);
            $this->participantService->syncParticipants($conversation, $participants);
            return $conversation;
        });
    }

    public function getForUser(Authenticatable $user, array $filters = []): LengthAwarePaginator
    {
        $query = Conversation::query();
        $isAdmin = $user instanceof Admin;
        $userId = $user->getAuthIdentifier();
        $userType = get_class($user);

        if (!$isAdmin) {
            $query->whereHas('participants', function (Builder $q) use ($user) {
                $q->where('participant_type', get_class($user))
                  ->where('participant_id', $user->getAuthIdentifier())
                  ->whereNull('left_at');
            });
        }

        $query->with(['participants.participant', 'latestMessage.sender']);

        $query->withCount([
            'messages as unread_count' => function ($q) use ($userId, $userType) {
                $q->whereDoesntHave('reads', function ($r) use ($userId, $userType) {
                    // Change these column names if your schema differs (e.g., 'admin_id')
                    $r->where('reader_id', $userId)
                      ->where('reader_type', $userType);
                });
            }
        ]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('subject', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('messages', fn(Builder $m) => $m->where('body', 'like', '%' . $filters['search'] . '%'));
            });
        }

        return $query->latest('last_message_at')->paginate($filters['per_page'] ?? 15);
    }

    public function archive(Conversation $conversation): Conversation
    {
        $conversation->update(['status' => 'archived']);
        $this->clearCache($conversation->id);
        return $conversation;
    }

    public function reopen(Conversation $conversation): Conversation
    {
        $conversation->update(['status' => 'active']);
        $this->clearCache($conversation->id);
        return $conversation;
    }

    public function close(Conversation $conversation): Conversation
    {
        $conversation->update(['status' => 'closed']);
        $this->clearCache($conversation->id);
        return $conversation;
    }

    public function delete(Conversation $conversation): bool
    {
        $conversation->delete();
        $this->clearCache($conversation->id);
        return true;
    }

    public function updateLastMessage(Conversation $conversation, Message $message): void
    {
        $conversation->update(['last_message_at' => $message->created_at]);
        $this->clearCache($conversation->id);
    }

    public function getStatistics(): array
    {
        $cacheKey = 'conversation_global_stats';
        return Cache::tags(['conversations'])->remember($cacheKey, 300, function () {
            return [
                'total' => Conversation::count(),
                'active' => Conversation::where('status', 'active')->count(),
                'archived' => Conversation::where('status', 'archived')->count(),
                'closed' => Conversation::where('status', 'closed')->count(),
            ];
        });
    }

    public function getConversationStatistics(Conversation $conversation): array
    {
        $cacheKey = "conversation_stats_{$conversation->id}";
        return Cache::tags(['conversations', "conversation_{$conversation->id}"])->remember($cacheKey, 300, function () use ($conversation) {
            return [
                'total_messages' => $conversation->messages()->count(),
                'total_participants' => $conversation->participants()->whereNull('left_at')->count(),
                'last_activity' => $conversation->last_message_at,
            ];
        });
    }

    private function clearCache(int $conversationId): void
    {
        Cache::tags(['conversations', "conversation_{$conversationId}"])->flush();
    }
}