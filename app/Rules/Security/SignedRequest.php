<?php

namespace App\Rules\Security;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SignedRequest implements ValidationRule
{
    public function __construct(
        protected string $secretKey,
        protected ?int $timestamp = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->timestamp || abs(time() - $this->timestamp) > 300) {
            $fail('Permintaan kadaluarsa atau timestamp tidak valid (maksimal 5 menit).');
            return;
        }

        $expectedSignature = hash_hmac('sha256', (string) $this->timestamp, $this->secretKey);

        if (!hash_equals($expectedSignature, (string) $value)) {
            $fail('Tanda tangan digital (HMAC signature) tidak valid.');
        }
    }
}