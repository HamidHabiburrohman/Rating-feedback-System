<?php

namespace App\Data\Rating;

readonly class ReplyRatingData
{
    /**
     * Create a new ReplyRatingData DTO instance.
     */
    public function __construct(
        public int $ratingId,
        public string $reply,
        public ?int $employeeId = null,
        public ?int $adminId = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ratingId: (int) $data['rating_id'],
            reply: $data['reply'],
            employeeId: isset($data['employee_id']) ? (int) $data['employee_id'] : null,
            adminId: isset($data['admin_id']) ? (int) $data['admin_id'] : null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'rating_id' => $this->ratingId,
            'reply' => $this->reply,
            'employee_id' => $this->employeeId,
            'admin_id' => $this->adminId,
        ], fn($value) => $value !== null);
    }
}