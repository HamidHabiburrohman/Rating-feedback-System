<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Conversation;

use App\Http\Controllers\Controller;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use App\Services\Admin\Conversation\MessageReadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class MessageReadController extends Controller
{
    public function __construct(
        private readonly MessageReadService $readService,
    ) {}

    public function markAsRead(Conversation $conversation, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('view', $conversation);

        $this->readService->markAsRead($message, auth('admin')->user());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message marked as read.',
            ]);
        }

        return back()->with('success', 'Message marked as read.');
    }

    public function markAllAsRead(Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('view', $conversation);

        $count = $this->readService->markAllAsRead($conversation, auth('admin')->user());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} messages marked as read.",
            ]);
        }

        return back()->with('success', "{$count} messages marked as read.");
    }

    public function unread(Conversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);

        $count = $this->readService->unreadCount($conversation, auth('admin')->user());

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
        ]);
    }

    public function history(Message $message): JsonResponse
    {
        $this->authorize('view', $message->conversation);

        $history = $this->readService->getReadHistory($message);

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }
}