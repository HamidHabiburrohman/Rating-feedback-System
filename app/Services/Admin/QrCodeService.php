<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Unit\QrCode;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QrCodeService
{
    protected array $searchableColumns = ['code'];
    protected array $filterableColumns = ['status', 'unit_id'];
    protected array $perPageOptions = [10, 25, 50, 100];

    public function __construct(
        protected string $disk = 'public',
        protected string $directory = 'qr-codes'
    ) {}

    public function getAllQrCodes(array $filters = []): LengthAwarePaginator
    {
        $query = QrCode::with(['unit', 'generatedByAdmin'])
            ->withCount(['unitVisits', 'ratings']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhereHas('unit', fn($u) => $u->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    });
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            } elseif ($filters['status'] === 'expired') {
                $query->where('expires_at', '<=', now());
            }
        }

        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        
        $allowedSorts = ['created_at', 'code', 'expires_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';
        
        $query->orderBy($sort, $order);

        $perPage = (int) ($filters['per_page'] ?? 10);
        if (!in_array($perPage, $this->perPageOptions)) {
            $perPage = 10;
        }

        return $query->paginate($perPage);
    }

    public function getQrCodeStats(): array
    {
        return Cache::tags(['qr-codes', 'stats'])->remember('qr_code_stats', 300, function () {
            $total = QrCode::count();
            $active = QrCode::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->count();
            $inactive = QrCode::where('is_active', false)->count();
            $expired = QrCode::where('expires_at', '<=', now())->count();

            return [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'expired' => $expired,
            ];
        });
    }

    public function getByUnit(int $unitId): ?QrCode
    {
        return Cache::remember("unit_qr_{$unitId}", 3600, function () use ($unitId): ?QrCode {
            return QrCode::where('unit_id', $unitId)
                ->where('is_active', true)
                ->first();
        });
    }

    public function generate(int $unitId, int $adminId): QrCode
    {
        $exists = QrCode::where('unit_id', $unitId)
            ->where('is_active', true)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'qr_code' => ['This unit already has an active QR Code. Please regenerate it instead.'],
            ]);
        }

        return DB::transaction(function () use ($unitId, $adminId): QrCode {
            $code = $this->generateUniqueCode();
            $path = $this->generateQrCodeImage($code, $unitId);

            $qrCode = QrCode::create([
                'unit_id' => $unitId,
                'code' => $code,
                'qr_image_path' => $path,
                'is_active' => true,
                'expires_at' => now()->addYear(),
                'generated_by_admin_id' => $adminId,
            ]);

            $this->clearCache($unitId);
            Cache::tags(['qr-codes', 'stats'])->flush();

            return $qrCode;
        });
    }

    public function regenerate(int $id, int $adminId): QrCode
    {
        return DB::transaction(function () use ($id, $adminId): QrCode {
            $qrCode = QrCode::findOrFail($id);
            $this->deleteOldImage($qrCode->qr_image_path);

            $code = $this->generateUniqueCode();
            $path = $this->generateQrCodeImage($code, $qrCode->unit_id);

            $qrCode->update([
                'code' => $code,
                'qr_image_path' => $path,
                'generated_by_admin_id' => $adminId,
                'expires_at' => now()->addYear(),
            ]);

            $this->clearCache($qrCode->unit_id);
            Cache::tags(['qr-codes', 'stats'])->flush();

            return $qrCode->fresh();
        });
    }

    public function activate(int $id): QrCode
    {
        return DB::transaction(function () use ($id): QrCode {
            $qrCode = QrCode::findOrFail($id);

            $conflict = QrCode::where('unit_id', $qrCode->unit_id)
                ->where('id', '!=', $id)
                ->where('is_active', true)
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'qr_code' => ['Another active QR Code already exists for this unit.'],
                ]);
            }

            $qrCode->update(['is_active' => true]);
            $this->clearCache($qrCode->unit_id);
            Cache::tags(['qr-codes', 'stats'])->flush();

            return $qrCode->fresh();
        });
    }

    public function deactivate(int $id): QrCode
    {
        return DB::transaction(function () use ($id): QrCode {
            $qrCode = QrCode::findOrFail($id);
            $qrCode->update(['is_active' => false]);
            $this->clearCache($qrCode->unit_id);
            Cache::tags(['qr-codes', 'stats'])->flush();

            return $qrCode->fresh();
        });
    }

    public function preview(int $id): string
    {
        $qrCode = QrCode::findOrFail($id);
        return asset('storage/' . $qrCode->qr_image_path);
    }

    public function download(int $id): BinaryFileResponse
    {
        $qrCode = QrCode::findOrFail($id);
        $path = Storage::disk($this->disk)->path($qrCode->qr_image_path);
        return response()->download($path, "unit-{$qrCode->unit_id}-qr.png");
    }

    private function generateUniqueCode(): string
    {
        return Str::uuid()->toString();
    }

    private function generateQrCodeImage(string $code, int $unitId): string
    {
        $path = $this->getStoragePath($unitId);
        $data = url("/student/qr/scan?code={$code}");

        $qrCode = new EndroidQrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        Storage::disk($this->disk)->put($path, $result->getString());

        return $path;
    }

    private function deleteOldImage(?string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    private function clearCache(int $unitId): void
    {
        Cache::forget("unit_qr_{$unitId}");
    }

    private function getStoragePath(int $unitId): string
    {
        return "{$this->directory}/unit-{$unitId}.png";
    }
}