<?php

namespace App\Rules\QrCode;

use App\Models\Unit\QrCode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveQrCode implements ValidationRule
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

        if (isset($qrCode->is_active) && !$qrCode->is_active) {
            $fail('QR Code ini dalam status tidak aktif atau telah dinonaktifkan oleh unit.');
        }
    }
}