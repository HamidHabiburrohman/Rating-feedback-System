<?php

namespace App\Rules\Rating;

use Closure;
use App\Models\Unit\Unit;
use App\Models\Unit\QrCode;
use Illuminate\Contracts\Validation\ValidationRule;

class CanRateUnit implements ValidationRule
{
    public function __construct(
        protected int $userId,
        protected int $unitId
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $unit = Unit::find($this->unitId);
        if (!$unit || !$unit->is_active) {
            $fail('Unit layanan saat ini tidak aktif atau tidak menerima ulasan.');
            return;
        }

        $hasScanned = QrCode::where('unit_id', $this->unitId)
            ->where('scanned_by_user_id', $this->userId)
            ->where('scanned_at', '>=', now()->subHours(24))
            ->exists();

        if (!$hasScanned) {
            $fail('Anda harus melakukan scan QR Code di unit ini terlebih dahulu sebelum memberikan rating.');
        }
    }
}