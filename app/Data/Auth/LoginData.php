<?php

namespace App\Data\Auth;

readonly class LoginData
{
    /**
     * Create a new LoginData DTO instance.
     */
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
        public ?string $guard = null
    ) {}

    /**
     * Form DTO from array or request input.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            remember: (bool) ($data['remember'] ?? false),
            guard: $data['guard'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return array_filter([
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
            'guard' => $this->guard,
        ], fn($value) => $value !== null);
    }
}