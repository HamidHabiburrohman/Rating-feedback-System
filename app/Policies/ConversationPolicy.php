<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Authentication\Admin;
use App\Models\Conversation\Conversation;

class ConversationPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Conversation $conversation): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Conversation $conversation): bool
    {
        return $conversation->participants()
            ->where('participant_type', get_class($admin))
            ->where('participant_id', $admin->id)
            ->whereNull('left_at')
            ->exists();
    }

    public function delete(Admin $admin, Conversation $conversation): bool
    {
        return $this->update($admin, $conversation);
    }
}