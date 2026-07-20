<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Conversation;

use App\Http\Controllers\Controller;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use App\Models\Conversation\MessageAttachment;
use App\Services\Admin\Conversation\MessageAttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class MessageAttachmentController extends Controller
{
    public function __construct(
        private readonly MessageAttachmentService $attachmentService,
    ) {}

    public function upload(Request $request, Conversation $conversation, Message $message): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $conversation);

        $files = $request->file('attachments');
        $attachments = $this->attachmentService->uploadMultiple($message, $files);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attachments uploaded successfully.',
                'data' => $attachments,
            ], 201);
        }

        return back()->with('success', 'Attachments uploaded successfully.');
    }

    public function replace(Request $request, MessageAttachment $attachment): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $attachment->message->conversation);

        $file = $request->file('attachment');
        $updatedAttachment = $this->attachmentService->replace($attachment, $file);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attachment replaced successfully.',
                'data' => $updatedAttachment,
            ]);
        }

        return back()->with('success', 'Attachment replaced successfully.');
    }

    public function destroy(MessageAttachment $attachment): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $attachment->message->conversation);

        $this->attachmentService->delete($attachment);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully.',
            ]);
        }

        return back()->with('success', 'Attachment deleted successfully.');
    }

    public function download(MessageAttachment $attachment): BinaryFileResponse
    {
        $this->authorize('view', $attachment->message->conversation);

        return $this->attachmentService->download($attachment);
    }

    public function preview(MessageAttachment $attachment): JsonResponse
    {
        $this->authorize('view', $attachment->message->conversation);

        $url = $this->attachmentService->preview($attachment);

        return response()->json([
            'success' => true,
            'data' => ['url' => $url],
        ]);
    }

    public function downloadDirect(\App\Models\Conversation\MessageAttachment $attachment): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorize('view', $attachment->message->conversation);
        return $this->attachmentService->download($attachment);
    }
}
