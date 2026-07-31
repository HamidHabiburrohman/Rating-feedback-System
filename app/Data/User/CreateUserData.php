<?php

namespace App\Data\User;

readonly class CreateUserData
{
    /**
     * Create a new CreateUserData DTO instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role,
        public ?int $unitId = null,
        public ?string $phone = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
            unitId: isset($data['unit_id']) ? (int) $data['unit_id'] : null,
            phone: $data['phone'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'unit_id' => $this->unitId,
            'phone' => $this->phone,
        ], fn($value) => $value !== null);
    }
}