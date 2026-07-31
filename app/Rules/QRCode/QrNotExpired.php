<?php

namespace App\Rules\QrCode;

use App\Models\Unit\QrCode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class QrNotExpired implements ValidationRule
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
        $qrCode = QrCode::where('token', $value)->first();

        if (!$qrCode) {
            $fail('QR Code tidak ditemukan.');
            return;
        }

        if ($qrCode->expires_at && now()->greaterThan($qrCode->expires_at)) {
            $fail('QR Code ini telah kedaluwarsa. Silakan pindaikan QR Code terbaru yang disediakan unit.');
        }
    }
}