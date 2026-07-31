<?php

namespace App\Rules\QrCode;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidQrSignature implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param string|null $token Token QR Code asli
     * @param int|null $timestamp Timestamp generasi QR
     */
    public function __construct(
        protected ?string $token = null,
        protected ?int $timestamp = null
    ) {}

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->token || !$this->timestamp || empty($value)) {
            $fail('Parameter tanda tangan digital QR Code tidak lengkap.');
            return;
        }

        $secretKey = config('app.key');
        $expectedSignature = hash_hmac('sha256', "{$this->token}:{$this->timestamp}", $secretKey);

        if (!hash_equals($expectedSignature, (string) $value)) {
            $fail('Tanda tangan digital (Signature) QR Code tidak valid atau telah dimodifikasi.');
        }
    }
}