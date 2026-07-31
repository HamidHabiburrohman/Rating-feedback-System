<?php

namespace App\Data\Report;

readonly class CreateReportData
{
    /**
     * Create a new CreateReportData DTO instance.
     */
    public function __construct(
        public int $unitId,
        public int $reportCategoryId,
        public string $title,
        public string $description,
        public ?int $studentId = null,
        public string $priority = 'medium',
        public ?int $ratingId = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            unitId: (int) $data['unit_id'],
            reportCategoryId: (int) $data['report_category_id'],
            title: $data['title'],
            description: $data['description'],
            studentId: isset($data['student_id']) ? (int) $data['student_id'] : null,
            priority: $data['priority'] ?? 'medium',
            ratingId: isset($data['rating_id']) ? (int) $data['rating_id'] : null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'unit_id' => $this->unitId,
            'report_category_id' => $this->reportCategoryId,
            'title' => $this->title,
            'description' => $this->description,
            'student_id' => $this->studentId,
            'priority' => $this->priority,
            'rating_id' => $this->ratingId,
        ], fn($value) => $value !== null);
    }
}