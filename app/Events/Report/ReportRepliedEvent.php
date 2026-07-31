<?php

namespace App\Events\Report;

use App\Models\Report\ReportReply;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportRepliedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param ReportReply $reply
     */
    public function __construct(public ReportReply $reply) {}
}