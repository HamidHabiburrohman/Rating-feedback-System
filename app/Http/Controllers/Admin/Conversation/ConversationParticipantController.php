<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Conversation;

use App\Http\Controllers\Controller;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationParticipant;
use App\Services\Admin\Conversation\ConversationParticipantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ConversationParticipantController extends Controller
{
    public function __construct(
        private readonly ConversationParticipantService $participantService,
    ) {}

    public function index(Conversation $conversation): View|JsonResponse
    {
        $this->authorize('view', $conversation);

        $participants = $this->participantService->listParticipants($conversation);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $participants,
            ]);
        }

        return view('admin.conversations.participants.index', compact('conversation', 'participants'));
    }

    public function store(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);

        // Asumsi FormRequest telah me-resolve user object atau service menerima ID
        $participant = $this->participantService->addParticipant(
            $conversation, 
            $request->user('admin')
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Participant added successfully.',
                'data' => $participant,
            ], 201);
        }

        return back()->with('success', 'Participant added successfully.');
    }

    public function destroy(Conversation $conversation, ConversationParticipant $participant): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);

        $this->participantService->removeParticipant($conversation, $participant->participant);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Participant removed successfully.',
            ]);
        }

        return back()->with('success', 'Participant removed successfully.');
    }

    public function leave(Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('view', $conversation);

        $this->participantService->leaveConversation($conversation, auth('admin')->user());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Left conversation successfully.',
            ]);
        }

        return redirect()
            ->route('admin.conversations.index')
            ->with('success', 'Left conversation successfully.');
    }
}