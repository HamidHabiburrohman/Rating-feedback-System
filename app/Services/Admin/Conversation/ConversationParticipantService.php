<?php

declare(strict_types=1);

namespace App\Services\Admin\Conversation;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationParticipant;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ConversationParticipantService
{
    public function addParticipant(Conversation $conversation, Authenticatable $user): ConversationParticipant
    {
        return ConversationParticipant::updateOrCreate(
            [
                'conversation_id' => $conversation->id,
                'participant_type' => get_class($user),
                'participant_id' => $user->getAuthIdentifier(),
            ],
            [
                'joined_at' => now(),
                'left_at' => null,
            ]
        );
    }

    public function removeParticipant(Conversation $conversation, Authenticatable $user): bool
    {
        return (bool) ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('participant_type', get_class($user))
            ->where('participant_id', $user->getAuthIdentifier())
            ->update(['left_at' => now()]);
    }

    public function leaveConversation(Conversation $conversation, Authenticatable $user): bool
    {
        return $this->removeParticipant($conversation, $user);
    }

    public function assignEmployee(Conversation $conversation, Employee $employee): ConversationParticipant
    {
        return $this->addParticipant($conversation, $employee);
    }

    public function listParticipants(Conversation $conversation): Collection
    {
        return $conversation->participants()
            ->whereNull('left_at')
            ->with('participant')
            ->get();
    }

    public function hasParticipant(Conversation $conversation, Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return true;
        }

        return $conversation->participants()
            ->where('participant_type', get_class($user))
            ->where('participant_id', $user->getAuthIdentifier())
            ->whereNull('left_at')
            ->exists();
    }

    public function syncParticipants(Conversation $conversation, array $users): void
    {
        DB::transaction(function () use ($conversation, $users) {
            $conversation->participants()->update(['left_at' => now()]);

            foreach ($users as $user) {
                $this->addParticipant($conversation, $user);
            }
        });
    }
}
