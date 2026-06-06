<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use App\Models\Units\QrCode;
use App\Models\Feedback\UnitVisit;

class QrValidationService
{
    protected Student $student;

    public function setStudent(Student $student): self
    {
        $this->student = $student;
        return $this;
    }

    public function validateQr(string $qrCode): ?array
    {
        $qr = QrCode::where('code', $qrCode)
            ->where('is_active', true)
            ->with('unit')
            ->first();

        if (!$qr) {
            return ['valid' => false, 'message' => 'QR Code tidak valid'];
        }

        if ($qr->expires_at && $qr->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'QR Code sudah kedaluwarsa'];
        }

        return [
            'valid' => true,
            'qr_code' => $qr,
            'unit' => $qr->unit,
        ];
    }

    public function validateGps(float $latitude, float $longitude, QrCode $qr): array
    {
        $unit = $qr->unit;

        if (!$unit->latitude || !$unit->longitude) {
            return ['valid' => true, 'distance' => null, 'message' => 'Lokasi unit tidak tersedia'];
        }

        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            (float) $unit->latitude,
            (float) $unit->longitude
        );

        $radius = 100;

        $isValid = $distance <= $radius;

        return [
            'valid' => $isValid,
            'distance' => round($distance, 2),
            'radius' => $radius,
            'message' => $isValid ? 'Validasi GPS berhasil' : 'Anda berada terlalu jauh dari unit',
        ];
    }

    public function createVisit(int $unitId, int $qrCodeId, float $latitude, float $longitude, bool $gpsValidated): UnitVisit
    {
        return UnitVisit::create([
            'unit_id' => $unitId,
            'student_id' => $this->student->id,
            'qr_code_id' => $qrCodeId,
            'visited_at' => now(),
            'is_gps_validated' => $gpsValidated,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'validation_radius_meters' => 100,
        ]);
    }

    public function hasActiveRating(int $unitId): bool
    {
        return $this->student->ratings()
            ->where('unit_id', $unitId)
            ->where('status', '!=', 'archived')
            ->exists();
    }

    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}