<?php

namespace App\Data\Report;

readonly class UpdateReportStatusData
{
    /**
     * Create a new UpdateReportStatusData DTO instance.
     */
    public function __construct(
        public int $reportId,
        public string $status,
        public ?string $notes = null,
        public ?int $updatedBy = null,
        public ?string $updaterType = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            reportId: (int) $data['report_id'],
            status: $data['status'],
            notes: $data['notes'] ?? null,
            updatedBy: isset($data['updated_by']) ? (int) $data['updated_by'] : null,
            updaterType: $data['updater_type'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'report_id' => $this->reportId,
            'status' => $this->status,
            'notes' => $this->notes,
            'updated_by' => $this->updatedBy,
            'updater_type' => $this->updaterType,
        ], fn($value) => $value !== null);
    }
}