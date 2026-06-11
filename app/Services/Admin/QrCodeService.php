<?php

namespace App\Services\Admin;

use App\Models\Unit\Unit;
use App\Models\Unit\QrCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeService extends BaseAdminService
{
    public function getByUnit(int $unitId)
    {
        $cacheKey = "admin_qr_codes_unit_{$unitId}";

        return Cache::tags(['units', "unit_{$unitId}", 'qr_codes'])->remember($cacheKey, 300, function () use ($unitId) {
            return QrCode::where('unit_id', $unitId)
                ->with(['unit', 'generatorAdmin'])
                ->latest()
                ->get();
        });
    }

    public function generate(int $unitId, int $adminId): QrCode
    {
        return DB::transaction(function () use ($unitId, $adminId) {
            $unit = Unit::findOrFail($unitId);
            $code = $this->generateUniqueCode($unit);

            $qrCode = QrCode::create([
                'unit_id' => $unitId,
                'code' => $code,
                'path' => $this->generateQrCodeImage($code, $unitId),
                'is_active' => true,
                'expires_at' => now()->addYear(),
                'last_generated_at' => now(),
                'generated_by_admin_id' => $adminId,
            ]);

            Cache::tags(['units', "unit_{$unitId}", 'qr_codes'])->flush();

            return $qrCode->fresh(['unit', 'generatorAdmin']);
        });
    }

    public function regenerate(int $id, int $adminId): QrCode
    {
        return DB::transaction(function () use ($id, $adminId) {
            $oldQrCode = QrCode::findOrFail($id);
            $unit = $oldQrCode->unit;

            $oldQrCode->update([
                'is_active' => false,
                'expires_at' => now(),
            ]);

            $newCode = $this->generateUniqueCode($unit);

            $newQrCode = QrCode::create([
                'unit_id' => $unit->id,
                'code' => $newCode,
                'path' => $this->generateQrCodeImage($newCode, $unit->id),
                'is_active' => true,
                'expires_at' => now()->addYear(),
                'last_generated_at' => now(),
                'generated_by_admin_id' => $adminId,
            ]);

            Cache::tags(['units', "unit_{$unit->id}", 'qr_codes'])->flush();

            return $newQrCode->fresh(['unit', 'generatorAdmin']);
        });
    }

    public function toggleActive(int $id): QrCode
    {
        return DB::transaction(function () use ($id) {
            $qrCode = QrCode::findOrFail($id);
            $qrCode->update(['is_active' => !$qrCode->is_active]);

            Cache::tags(['units', "unit_{$qrCode->unit_id}", 'qr_codes'])->flush();

            return $qrCode->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $qrCode = QrCode::findOrFail($id);
            $unitId = $qrCode->unit_id;

            $qrCode->delete();

            Cache::tags(['units', "unit_{$unitId}", 'qr_codes'])->flush();
            return true;
        });
    }

    protected function generateUniqueCode(Unit $unit): string
    {
        do {
            $code = strtoupper($unit->code . '-' . Str::random(8));
        } while (QrCode::where('code', $code)->exists());

        return $code;
    }

    protected function generateQrCodeImage(string $code, int $unitId): ?string
    {
        try {
            if (!class_exists(QrCodeGenerator::class)) {
                return null;
            }

            $qrData = url("/student/qr/scan?code={$code}");
            $fileName = "qr_codes/unit_{$unitId}_{$code}.svg";
            $path = storage_path('app/public/' . $fileName);

            $directory = dirname($path);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            QrCodeGenerator::size(300)
                ->format('svg')
                ->generate($qrData, $path);

            return $fileName;
        } catch (\Exception $e) {
            return null;
        }
    }
}