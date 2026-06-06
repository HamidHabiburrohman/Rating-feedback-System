<?php

namespace App\Services\Shared;

class GpsValidationService
{
    protected int $earthRadius = 6371000;

    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $this->earthRadius * $c;
    }

    public function isValid(float $lat1, float $lng1, float $lat2, float $lng2, int $radiusMeters = 100): bool
    {
        $distance = $this->calculateDistance($lat1, $lng1, $lat2, $lng2);
        return $distance <= $radiusMeters;
    }

    public function getDistanceInMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        return round($this->calculateDistance($lat1, $lng1, $lat2, $lng2), 2);
    }

    public function getDistanceInKilometers(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        return round($this->calculateDistance($lat1, $lng1, $lat2, $lng2) / 1000, 2);
    }
}