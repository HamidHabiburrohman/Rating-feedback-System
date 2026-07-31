<?php

namespace App\Rules\QrCode;

use App\Models\Unit\QrCode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidQrToken implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || !is_string($value)) {
            $fail('Format Token QR Code tidak valid.');
            return;
        }

        $exists = QrCode::where('token', $value)->exists();

        if (!$exists) {
            $fail('Token QR Code tidak ditemukan atau tidak terdaftar pada sistem.');
        }
    }
}