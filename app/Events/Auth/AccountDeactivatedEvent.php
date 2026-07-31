<?php

namespace App\Events\Auth;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountDeactivatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Model $user
     * @param string|null $reason
     * @param int|null $deactivatedBy
     */
    public function __construct(
        public Model $user,
        public ?string $reason = null,
        public ?int $deactivatedBy = null
    ) {}
}