<?php

namespace App\Services\Admin;

use App\Models\Units\QrCode;
use App\Models\Units\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class QrCodeService
{
    public function getByUnit(int $unitId): Collection
    {
        return QrCode::where('unit_id', $unitId)->get();
    }

    public function findById(int $id): ?QrCode
    {
        return QrCode::find($id);
    }

    public function findByCode(string $code): ?QrCode
    {
        return QrCode::where('code', $code)->first();
    }

    public function generateForUnit(Unit $unit, int $adminId): QrCode
    {
        $code = $this->generateUniqueCode();

        return QrCode::create([
            'unit_id' => $unit->id,
            'code' => $code,
            'generated_by_admin_id' => $adminId,
            'last_generated_at' => now(),
            'is_active' => true,
        ]);
    }

    public function regenerate(int $qrCodeId, int $adminId): ?QrCode
    {
        $qrCode = $this->findById($qrCodeId);
        if (!$qrCode) {
            return null;
        }

        $qrCode->code = $this->generateUniqueCode();
        $qrCode->last_generated_at = now();
        $qrCode->save();

        return $qrCode;
    }

    public function toggleActive(int $id): bool
    {
        $qrCode = $this->findById($id);
        if (!$qrCode) {
            return false;
        }
        $qrCode->is_active = !$qrCode->is_active;
        return $qrCode->save();
    }

    public function delete(int $id): bool
    {
        $qrCode = $this->findById($id);
        if (!$qrCode) {
            return false;
        }
        return $qrCode->delete();
    }

    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (QrCode::where('code', $code)->exists());

        return $code;
    }
}