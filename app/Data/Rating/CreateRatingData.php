<?php

namespace App\Data\Rating;

readonly class CreateRatingData
{
    /**
     * Create a new CreateRatingData DTO instance.
     */
    public function __construct(
        public int $unitId,
        public int $score,
        public ?int $studentId = null,
        public ?int $unitVisitId = null,
        public ?string $review = null,
        public bool $isAnonymous = false
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            unitId: (int) $data['unit_id'],
            score: (int) $data['score'],
            studentId: isset($data['student_id']) ? (int) $data['student_id'] : null,
            unitVisitId: isset($data['unit_visit_id']) ? (int) $data['unit_visit_id'] : null,
            review: $data['review'] ?? null,
            isAnonymous: (bool) ($data['is_anonymous'] ?? false)
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'unit_id' => $this->unitId,
            'score' => $this->score,
            'student_id' => $this->studentId,
            'unit_visit_id' => $this->unitVisitId,
            'review' => $this->review,
            'is_anonymous' => $this->isAnonymous,
        ], fn($value) => $value !== null);
    }
}