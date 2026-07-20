<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin\Conversation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Conversation\Message\MessageFilterRequest;
use App\Http\Requests\Admin\Conversation\Message\StoreMessageRequest;
use App\Http\Requests\Admin\Conversation\Message\UpdateMessageRequest;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use App\Services\Admin\Conversation\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
final class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    ) {}
    public function index(MessageFilterRequest $request, Conversation $conversation): View|JsonResponse
    {
        $this->authorize('view', $conversation);
        $messages = $this->messageService->getPaginated(
            $conversation,
            (int) $request->validated('per_page', 20)
        );
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $messages,
            ]);
        }
        return view('admin.conversations.index', compact('conversation', 'messages'));
    }
    public function show(Conversation $conversation, Message $message): View|JsonResponse
    {
        $this->authorize('view', $conversation);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $message->load(['sender', 'attachments']),
            ]);
        }
        return view('admin.conversations.show', compact('conversation', 'message'));
    }
    public function store(StoreMessageRequest $request): JsonResponse|RedirectResponse
    {
        $conversation = $request->input('conversation_id')
            ? Conversation::find($request->input('conversation_id'))
            : null;
        $message = $this->messageService->send(
            $conversation,
            auth('admin')->user(),
            $request->validated('body', ''),
            $request->validated('attachments'),
            $request->validated('subject'),
            $request->validated('participants'),
            $request->input('reply_to_id')
        );
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.conversations.messages.bubble', [
                'message' => $message,
                'isOwn' => true
            ])->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'message_id' => $message->id,
            ], 201);
        }
        return redirect()
            ->route('admin.conversations.show', $message->conversation_id)
            ->with('success', 'Message sent successfully.');
    }
    public function update(UpdateMessageRequest $request, Conversation $conversation, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);
        $updatedMessage = $this->messageService->edit(
            $message,
            auth('admin')->user(),
            $request->validated('body')
        );
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully.',
                'data' => $updatedMessage,
            ]);
        }
        return back()->with('success', 'Message updated successfully.');
    }
    public function destroy(Conversation $conversation, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);
        $this->messageService->delete($message, auth('admin')->user());
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully.',
            ]);
        }
        return back()->with('success', 'Message deleted successfully.');
    }
    public function search(MessageFilterRequest $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);
        $results = $this->messageService->search(
            $conversation,
            $request->validated('search', '')
        );
        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }
    public function latest(Conversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);
        $messages = $this->messageService->getLatest($conversation);
        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }
}