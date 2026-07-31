<?php

namespace App\Data\Unit;

readonly class CreateUnitData
{
    /**
     * Create a new CreateUnitData DTO instance.
     */
    public function __construct(
        public string $code,
        public string $name,
        public int $unitTypeId,
        public int $unitDepartmentId,
        public ?string $slug = null,
        public ?string $location = null,
        public ?string $phone = null,
        public ?string $email = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            unitTypeId: (int) $data['unit_type_id'],
            unitDepartmentId: (int) $data['unit_department_id'],
            slug: $data['slug'] ?? null,
            location: $data['location'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'code' => $this->code,
            'name' => $this->name,
            'unit_type_id' => $this->unitTypeId,
            'unit_department_id' => $this->unitDepartmentId,
            'slug' => $this->slug,
            'location' => $this->location,
            'phone' => $this->phone,
            'email' => $this->email,
        ], fn($value) => $value !== null);
    }
}