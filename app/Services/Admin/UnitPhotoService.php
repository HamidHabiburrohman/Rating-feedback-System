<?php

namespace App\Services\Admin;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UnitPhotoService extends BaseAdminService
{
    public function getByUnit(int $unitId)
    {
        return UnitPhoto::where('unit_id', $unitId)
            ->orderBy('is_primary', 'desc')
            ->orderBy('sort_order')
            ->get();
    }

    public function uploadMultiple(int $unitId, array $files): array
    {
        return DB::transaction(function () use ($unitId, $files) {
            $unit = Unit::findOrFail($unitId);
            $uploaded = [];
            $maxOrder = UnitPhoto::where('unit_id', $unitId)->max('sort_order') ?? 0;

            foreach ($files as $index => $file) {
                $path = $file->store('units/photos', 'public');
                $thumbnailPath = $this->generateThumbnail($file);

                $photo = UnitPhoto::create([
                    'unit_id' => $unitId,
                    'original_path' => $path,
                    'thumbnail_path' => $thumbnailPath,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'is_primary' => $unit->photos()->count() === 0 && $index === 0,
                    'sort_order' => $maxOrder + $index + 1,
                    'uploaded_by_admin_id' => $this->getAdminId(),
                ]);

                $uploaded[] = $photo;
            }

            Cache::tags(['units', "unit_{$unitId}", 'landing'])->flush();

            return $uploaded;
        });
    }

    public function setPrimary(int $photoId, int $unitId): bool
    {
        return DB::transaction(function () use ($photoId, $unitId) {
            UnitPhoto::where('unit_id', $unitId)->update(['is_primary' => false]);
            UnitPhoto::where('id', $photoId)->where('unit_id', $unitId)->update(['is_primary' => true]);

            Cache::tags(['units', "unit_{$unitId}", 'landing'])->flush();

            return true;
        });
    }

    public function reorder(int $unitId, array $orders): bool
    {
        return DB::transaction(function () use ($unitId, $orders) {
            foreach ($orders as $order) {
                UnitPhoto::where('id', $order['id'])
                    ->where('unit_id', $unitId)
                    ->update(['sort_order' => $order['sort_order']]);
            }

            Cache::tags(['units', "unit_{$unitId}", 'landing'])->flush();
            return true;
        });
    }

    public function delete(int $photoId, int $unitId): bool
    {
        return DB::transaction(function () use ($photoId, $unitId) {
            $photo = UnitPhoto::where('id', $photoId)->where('unit_id', $unitId)->firstOrFail();

            if ($photo->is_primary && UnitPhoto::where('unit_id', $unitId)->count() > 1) {
                $nextPrimary = UnitPhoto::where('unit_id', $unitId)
                    ->where('id', '!=', $photoId)
                    ->orderBy('sort_order')
                    ->first();
                
                if ($nextPrimary) {
                    $nextPrimary->update(['is_primary' => true]);
                }
            }

            if ($photo->original_path && Storage::disk('public')->exists($photo->original_path)) {
                Storage::disk('public')->delete($photo->original_path);
            }
            if ($photo->thumbnail_path && Storage::disk('public')->exists($photo->thumbnail_path)) {
                Storage::disk('public')->delete($photo->thumbnail_path);
            }

            $photo->delete();
            Cache::tags(['units', "unit_{$unitId}", 'landing'])->flush();

            return true;
        });
    }

    protected function generateThumbnail(UploadedFile $file): ?string
    {
        try {
            if (!extension_loaded('gd') && !extension_loaded('imagick')) {
                return null;
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->scaleDown(width: 400);

            $thumbnailPath = 'units/thumbnails/' . uniqid() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->put($thumbnailPath, (string) $image->encode());

            return $thumbnailPath;
        } catch (\Exception $e) {
            return null;
        }
    }
}