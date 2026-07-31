<?php

namespace App\Data\Conversation;

readonly class SendMessageData
{
    /**
     * Create a new SendMessageData DTO instance.
     */
    public function __construct(
        public int $conversationId,
        public int $senderId,
        public string $senderType,
        public string $body,
        public ?string $attachmentPath = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            conversationId: (int) $data['conversation_id'],
            senderId: (int) $data['sender_id'],
            senderType: $data['sender_type'],
            body: $data['body'],
            attachmentPath: $data['attachment_path'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'conversation_id' => $this->conversationId,
            'sender_id' => $this->senderId,
            'sender_type' => $this->senderType,
            'body' => $this->body,
            'attachment_path' => $this->attachmentPath,
        ], fn($value) => $value !== null);
    }
}