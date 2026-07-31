<?php

namespace App\Rules\Gps;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WithinUnitRadius implements ValidationRule
{
    public function __construct(
        protected float $unitLat,
        protected float $unitLng,
        protected float $maxRadiusMeters = 100.0
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value) || !isset($value['latitude'], $value['longitude'])) {
            $fail('Format koordinat tidak valid.');
            return;
        }

        $distance = $this->calculateHaversine(
            (float) $value['latitude'],
            (float) $value['longitude'],
            $this->unitLat,
            $this->unitLng
        );

        if ($distance > $this->maxRadiusMeters) {
            $fail("Lokasi Anda berada di luar jangkauan unit (Maksimal {$this->maxRadiusMeters} meter).");
        }
    }

    private function calculateHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}