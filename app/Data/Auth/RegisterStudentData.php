<?php

namespace App\Data\Auth;

readonly class RegisterStudentData
{
    /**
     * Create a new RegisterStudentData DTO instance.
     */
    public function __construct(
        public string $nim,
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            nim: $data['nim'],
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            phone: $data['phone'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'nim' => $this->nim,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
        ], fn($value) => $value !== null);
    }
}