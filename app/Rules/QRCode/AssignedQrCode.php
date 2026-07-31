<?php

namespace App\Rules\QrCode;

use App\Models\Unit\QrCode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AssignedQrCode implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param int|null $expectedUnitId ID unit yang seharusnya memiliki QR ini
     */
    public function __construct(
        protected ?int $expectedUnitId = null
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
        $qrCode = QrCode::where('token', $value)->first();

        if (!$qrCode) {
            $fail('QR Code tidak ditemukan.');
            return;
        }

        if (empty($qrCode->unit_id)) {
            $fail('QR Code ini belum ditautkan ke unit mana pun.');
            return;
        }

        if ($this->expectedUnitId !== null && (int) $qrCode->unit_id !== (int) $this->expectedUnitId) {
            $fail('QR Code tidak sesuai dengan unit lokasi yang dipilih.');
        }
    }
}