<?php

namespace App\Services\Student;

use App\Models\Unit\QrCode;
use App\Models\Unit\Unit;
use App\Models\Feedback\UnitVisit;
use App\Models\Feedback\Rating;
use Illuminate\Support\Facades\Cache;

class QrValidationService
{
    /**
     * Validasi QR Code berdasarkan code string
     */
    public function validateQrCode(string $code): array
    {
        $cacheKey = "qr_validation_{$code}";
        
        return Cache::tags(['qr_codes'])->remember($cacheKey, 300, function () use ($code) {
            $qr = QrCode::where('code', $code)
                ->with(['unit.unitType', 'unit.unitDepartment', 'unit.primaryPhoto'])
                ->first();

            if (!$qr) {
                return [
                    'valid' => false,
                    'message' => 'QR Code tidak valid atau tidak ditemukan',
                    'unit' => null,
                    'qr_code' => null
    ];
            }

            if (!$qr->is_active) {
                return [
                    'valid' => false,
                    'message' => 'QR Code sudah tidak aktif',
                    'unit' => null,
                    'qr_code' => null
    ];
            }

            if ($qr->expires_at && $qr->expires_at->isPast()) {
                return [
                    'valid' => false,
                    'message' => 'QR Code sudah kedaluwarsa',
                    'unit' => null,
                    'qr_code' => null
    ];
            }

            $unit = $qr->unit;
            if (!$unit || !$unit->is_active) {
                return [
                    'valid' => false,
                    'message' => 'Unit terkait tidak aktif',
                    'unit' => null,
                    'qr_code' => null
    ];
            }

            return [
                'valid' => true,
                'message' => 'QR Code valid',
                'qr_code' => $qr,
                'unit' => $unit
    ];
        });
    }

    /**
     * Alias untuk backward compatibility (jika ada yang masih panggil validateQr)
     */
    public function validateQr(string $qrCode): array
    {
        return $this->validateQrCode($qrCode);
    }

    /**
     * Cek status operasional unit
     */
    public function checkUnitStatus(int $unitId): array
    {
        $cacheKey = "unit_status_{$unitId}";
        
        return Cache::tags(['units', "unit_{$unitId}"])->remember($cacheKey, 60, function () use ($unitId) {
            $unit = Unit::findOrFail($unitId);

            $canRate = $unit->is_active && $unit->operational_status === 'open';

            return [
                'is_active' => $unit->is_active,
                'operational_status' => $unit->operational_status,
                'can_rate' => $canRate,
                'message' => match($unit->operational_status) {
                    'open' => 'Unit sedang buka dan menerima rating',
                    'full' => 'Unit sedang penuh kapasitas',
                    'maintenance' => 'Unit sedang dalam maintenance',
                    'closed' => 'Unit sedang tutup',
                    default => 'Status tidak diketahui',
                },
                'capacity' => $unit->capacity,
                'open_time' => $unit->open_time,
                'close_time' => $unit->close_time
    ];
        });
    }

    /**
     * Validasi GPS - hitung jarak antara student dan unit
     */
    public function validateGps(float $latitude, float $longitude, QrCode $qr): array
    {
        $unit = $qr->unit;
        
        // Ambil koordinat dari metadata JSON
        $unitLat = $unit->metadata['latitude'] ?? null;
        $unitLng = $unit->metadata['longitude'] ?? null;

        if (!$unitLat || !$unitLng) {
            return [
                'valid' => true,
                'distance' => null,
                'radius' => null,
                'message' => 'Validasi GPS tidak tersedia untuk unit ini'
    ];
        }

        $radius = 100; // meter
        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            (float) $unitLat,
            (float) $unitLng
        );

        $isValid = $distance <= $radius;

        return [
            'valid' => $isValid,
            'distance' => round($distance, 2),
            'radius' => $radius,
            'message' => $isValid 
                ? 'Validasi GPS berhasil' 
                : "Anda berada terlalu jauh dari unit ({$distance}m > {$radius}m)"
    ];
    }

    /**
     * Create unit visit record
     */
    public function createVisit(
        int $unitId, 
        int $studentId,
        ?int $qrCodeId, 
        ?float $latitude, 
        ?float $longitude, 
        bool $gpsValidated
    ): UnitVisit {
        return UnitVisit::create([
            'unit_id' => $unitId,
            'student_id' => $studentId,
            'qr_code_id' => $qrCodeId,
            'visited_at' => now(),
            'is_gps_validated' => $gpsValidated,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'validation_radius_meters' => 100,
        ]);
    }

    /**
     * Cek apakah student sudah punya rating aktif untuk unit tertentu
     */
    public function hasActiveRating(int $unitId, int $studentId): bool
    {
        $cacheKey = "student_{$studentId}_unit_{$unitId}_rated";
        
        return Cache::tags(['ratings', "student_{$studentId}"])->remember($cacheKey, 600, function () use ($unitId, $studentId) {
            return Rating::where('unit_id', $unitId)
                ->where('student_id', $studentId)
                ->where('status', '!=', 'archived')
                ->exists();
        });
    }

    /**
     * Haversine formula untuk hitung jarak 2 titik GPS
     */
    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    /**
     * Clear cache QR validation (dipanggil saat QR di-regenerate)
     */
    public function clearQrCache(string $code): void
    {
        Cache::tags(['qr_codes'])->forget("qr_validation_{$code}");
    }
}