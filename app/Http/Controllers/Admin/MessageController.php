<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Message\MessageFilterRequest;
use App\Http\Requests\Admin\Message\StoreMessageRequest;
use App\Http\Requests\Admin\Message\UpdateMessageRequest;
use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Conversation\Message;
use App\Services\Admin\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MessageController extends Controller
{
    public function __construct(
        protected readonly MessageService $service
    ) {}

    public function index(MessageFilterRequest $request, EmployeeUnitAssignment $assignment): View|JsonResponse
    {
        $this->authorize('view', $assignment);

        $messages = $this->service->getConversation($assignment, $request->validated());
        $unreadCount = $this->service->getUnreadCount($assignment, auth('admin')->user());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'messages' => $messages,
                'unread_count' => $unreadCount,
            ]);
        }

        return view('admin.assignments.messages.index', compact('assignment', 'messages', 'unreadCount'));
    }

    public function show(EmployeeUnitAssignment $assignment, Message $message): View|JsonResponse
    {
        $this->authorize('view', $assignment);

        if ($message->employee_unit_assignment_id !== $assignment->id) {
            abort(404);
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['message' => $message->load('sender')]);
        }

        return view('admin.assignments.messages.show', compact('assignment', 'message'));
    }

    public function store(StoreMessageRequest $request, EmployeeUnitAssignment $assignment): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $assignment);

        $message = $this->service->store($assignment, $request->validated(), auth('admin')->user());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully.',
                'data' => $message,
            ], 201);
        }

        return back()->with('success', 'Message sent successfully.');
    }

    public function update(UpdateMessageRequest $request, EmployeeUnitAssignment $assignment, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $assignment);

        if ($message->employee_unit_assignment_id !== $assignment->id) {
            abort(404);
        }

        $updatedMessage = $this->service->update($message, $request->validated(), auth('admin')->user());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully.',
                'data' => $updatedMessage,
            ]);
        }

        return back()->with('success', 'Message updated successfully.');
    }

    public function destroy(EmployeeUnitAssignment $assignment, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $assignment);

        if ($message->employee_unit_assignment_id !== $assignment->id) {
            abort(404);
        }

        $this->service->delete($message);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully.',
            ]);
        }

        return back()->with('success', 'Message deleted successfully.');
    }

    public function markAsRead(EmployeeUnitAssignment $assignment, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('view', $assignment);

        if ($message->employee_unit_assignment_id !== $assignment->id) {
            abort(404);
        }

        $this->service->markAsRead($message);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Message marked as read.']);
        }

        return back()->with('success', 'Message marked as read.');
    }

    public function markAllAsRead(EmployeeUnitAssignment $assignment): JsonResponse|RedirectResponse
    {
        $this->authorize('view', $assignment);

        $this->service->markAllAsRead($assignment, auth('admin')->user());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All messages marked as read.']);
        }

        return back()->with('success', 'All messages marked as read.');
    }

    public function downloadAttachment(EmployeeUnitAssignment $assignment, Message $message): BinaryFileResponse
    {
        $this->authorize('view', $assignment);

        if ($message->employee_unit_assignment_id !== $assignment->id) {
            abort(404);
        }

        return $this->service->downloadAttachment($message);
    }
}
