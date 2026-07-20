<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Conversation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Conversation\Conversation\ConversationFilterRequest;
use App\Models\Conversation\Conversation;
use App\Services\Admin\Conversation\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ConversationController extends Controller
{
    public function __construct(
        private readonly ConversationService $conversationService
    ) {}

    public function index(ConversationFilterRequest $request): View|JsonResponse
    {
        $this->authorize('viewAny', Conversation::class);

        $conversations = $this->conversationService->getForUser(
            auth('admin')->user(),
            $request->validated()
        );

        $statistics = $this->conversationService->getStatistics();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $conversations,
                'statistics' => $statistics,
            ]);
        }

        return view('admin.conversations.index', compact('conversations', 'statistics'));
    }

    public function show(Conversation $conversation): View|JsonResponse
    {
        $this->authorize('view', $conversation);

        $conversationData = $this->conversationService->find($conversation->id);
        $statistics = $this->conversationService->getConversationStatistics($conversation);
        $messages = $conversation->messages()
            ->with(['sender', 'attachments', 'reads', 'replyTo.sender'])
            ->oldest()
            ->get();
        $sharedFiles = \App\Models\Conversation\MessageAttachment::whereHas('message', fn($q) => $q->where('conversation_id', $conversation->id))->latest()->get();

        if (request()->ajax() || request()->wantsJson()) {
            // Kembalikan HANYA fragment show.blade.php (tanpa @extends)
            return view('admin.conversations.show', compact('conversation', 'messages', 'sharedFiles', 'statistics'));
        }

        // Jika user akses URL langsung (refresh), kembalikan full shell index dengan data pre-loaded
        return view('admin.conversations.index', [
            'conversations' => $this->conversationService->getForUser(auth('admin')->user()),
            'statistics' => $this->conversationService->getStatistics(),
            'conversation' => $conversationData,
            'messages' => $messages,
            'sharedFiles' => $sharedFiles
        ]);
    }
    public function archive(Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);

        $this->conversationService->archive($conversation);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conversation archived successfully.',
            ]);
        }

        return back()->with('success', 'Conversation archived successfully.');
    }

    public function close(Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);

        $this->conversationService->close($conversation);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conversation closed successfully.',
            ]);
        }

        return back()->with('success', 'Conversation closed successfully.');
    }

    public function reopen(Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);

        $this->conversationService->reopen($conversation);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conversation reopened successfully.',
            ]);
        }

        return back()->with('success', 'Conversation reopened successfully.');
    }

    public function destroy(Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $conversation);

        $this->conversationService->delete($conversation);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Conversation deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.conversations.index')
            ->with('success', 'Conversation deleted successfully.');
    }
}
