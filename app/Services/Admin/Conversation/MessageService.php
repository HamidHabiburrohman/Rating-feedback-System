<?php
declare(strict_types=1);
namespace App\Services\Admin\Conversation;
use App\Models\Authentication\Admin;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class MessageService
{
    public const MAX_BODY_LENGTH = 5000;
    public const MAX_ATTACHMENTS = 10;
    public const MAX_FILE_SIZE = 10 * 1024 * 1024;

    public function __construct(
        private readonly ConversationService $conversationService,
        private readonly MessageAttachmentService $attachmentService,
        private readonly ConversationParticipantService $participantService
    ) {}

    public function send(
        ?Conversation $conversation,
        Authenticatable $sender,
        ?string $body = '',
        ?array $attachments = null,
        ?string $subject = null,
        ?array $participants = null,
        ?int $replyToId = null
    ): Message {
        return DB::transaction(function () use ($conversation, $sender, $body, $attachments, $subject, $participants, $replyToId) {
            $body = (string) ($body ?? '');

            if (!$conversation) {
                if (empty($subject) || empty($participants)) {
                    throw ValidationException::withMessages([
                        'conversation' => ['Subject and participants are required for new conversations.'],
                    ]);
                }
                $conversation = $this->conversationService->findOrCreate($subject, $participants);
            }

            $this->validateSenderIsParticipant($conversation, $sender);
            $this->validateConversationIsActive($conversation);

            if (mb_strlen($body) > self::MAX_BODY_LENGTH) {
                throw ValidationException::withMessages([
                    'body' => ['Message body cannot exceed ' . self::MAX_BODY_LENGTH . ' characters.'],
                ]);
            }

            if ($attachments && count($attachments) > self::MAX_ATTACHMENTS) {
                throw ValidationException::withMessages([
                    'attachments' => ['Cannot upload more than ' . self::MAX_ATTACHMENTS . ' attachments.'],
                ]);
            }

            $messageData = [
                'conversation_id' => $conversation->id,
                'sender_type' => get_class($sender),
                'sender_id' => $sender->getAuthIdentifier(),
                'body' => $body,
                'type' => empty($attachments) ? 'text' : 'attachment',
            ];

            if ($replyToId) {
                $replyMessage = Message::where('conversation_id', $conversation->id)->find($replyToId);
                if ($replyMessage) {
                    $messageData['reply_to_id'] = $replyToId;
                }
            }

            $message = Message::create($messageData);

            if (!empty($attachments)) {
                $this->attachmentService->uploadMultiple($message, $attachments);
            }

            $this->conversationService->updateLastMessage($conversation, $message);

            return $message->load([
                'sender',
                'attachments',
                'replyTo',
                'replyTo.sender'
            ]);
        });
    }

    public function edit(Message $message, Authenticatable $user, string $body): Message
    {
        $this->validateMessageOwnership($message, $user);

        if (mb_strlen($body) > self::MAX_BODY_LENGTH) {
            throw ValidationException::withMessages([
                'body' => ['Message body cannot exceed ' . self::MAX_BODY_LENGTH . ' characters.'],
            ]);
        }

        $message->update([
            'body' => $body,
            'edited_at' => now(),
        ]);

        return $message;
    }

    public function delete(Message $message, Authenticatable $user): bool
    {
        $this->validateMessageOwnership($message, $user);
        return (bool) $message->delete();
    }

    public function restore(int $messageId, Authenticatable $user): Message
    {
        $message = Message::withTrashed()->findOrFail($messageId);
        $this->validateMessageOwnership($message, $user);
        $message->restore();
        return $message;
    }

    public function quote(Message $message): array
    {
        return [
            'id' => $message->id,
            'sender' => $message->sender->name ?? 'Unknown',
            'body' => $message->body,
            'created_at' => $message->created_at->toIso8601String(),
        ];
    }

    public function forward(Message $message, Conversation $targetConversation, Authenticatable $sender): Message
    {
        $this->validateSenderIsParticipant($targetConversation, $sender);
        $forwardedBody = "Forwarded message:\n" . ($message->body ?? '');

        return DB::transaction(function () use ($targetConversation, $sender, $forwardedBody, $message) {
            $newMessage = Message::create([
                'conversation_id' => $targetConversation->id,
                'sender_type' => get_class($sender),
                'sender_id' => $sender->getAuthIdentifier(),
                'body' => $forwardedBody,
                'type' => 'text',
            ]);

            $this->conversationService->updateLastMessage($targetConversation, $newMessage);
            return $newMessage;
        });
    }

    public function search(Conversation $conversation, string $query): Collection
    {
        return $conversation->messages()
            ->where('body', 'like', '%' . $query . '%')
            ->with('sender', 'attachments')
            ->latest()
            ->get();
    }

    public function getLatest(Conversation $conversation, int $limit = 20): Collection
    {
        return $conversation->messages()
            ->with('sender', 'attachments')
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function getPaginated(Conversation $conversation, int $perPage = 20): LengthAwarePaginator
    {
        return $conversation->messages()
            ->with('sender', 'attachments')
            ->latest()
            ->paginate($perPage);
    }

    private function validateSenderIsParticipant(Conversation $conversation, Authenticatable $user): void
    {
        if ($user instanceof Admin) {
            return;
        }

        if (!$this->participantService->hasParticipant($conversation, $user)) {
            throw ValidationException::withMessages([
                'conversation' => ['You are not a participant of this conversation.'],
            ]);
        }
    }

    private function validateConversationIsActive(Conversation $conversation): void
    {
        if ($conversation->status !== 'active') {
            throw ValidationException::withMessages([
                'conversation' => ['Cannot send messages to an inactive conversation.'],
            ]);
        }
    }

    private function validateMessageOwnership(Message $message, Authenticatable $user): void
    {
        if ($message->sender_type !== get_class($user) || $message->sender_id !== $user->getAuthIdentifier()) {
            throw ValidationException::withMessages([
                'message' => ['You do not have permission to modify this message.'],
            ]);
        }
    }
}