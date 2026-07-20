<?php

namespace App\Services\Admin\Unit;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            $isFirstPhoto = $unit->photos()->count() === 0;

            foreach ($files as $index => $file) {
                $fileName = uniqid('unit_') . '.' . $file->getClientOriginalExtension();
                $originalPath = $file->storeAs('units/photos', $fileName, 'public');
                $paths = $this->generateImageSizes($file, $fileName);

                $photo = UnitPhoto::create([
                    'unit_id' => $unitId,
                    'original_path' => $originalPath,
                    'thumbnail_path' => $paths['thumbnail'],
                    'medium_path' => $paths['medium'],
                    'large_path' => $paths['large'],
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'is_primary' => $isFirstPhoto && $index === 0,
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
            UnitPhoto::where('id', $photoId)
                ->where('unit_id', $unitId)
                ->update(['is_primary' => true]);
            
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
            $photo = UnitPhoto::where('id', $photoId)
                ->where('unit_id', $unitId)
                ->firstOrFail();

            if ($photo->is_primary) {
                $nextPrimary = UnitPhoto::where('unit_id', $unitId)
                    ->where('id', '!=', $photoId)
                    ->orderBy('sort_order')
                    ->first();
                
                if ($nextPrimary) {
                    $nextPrimary->update(['is_primary' => true]);
                }
            }

            $this->deletePhotoFiles($photo);
            $photo->delete();

            Cache::tags(['units', "unit_{$unitId}", 'landing'])->flush();
            return true;
        });
    }

    protected function generateImageSizes(UploadedFile $file, string $fileName): array
    {
        $paths = [
            'thumbnail' => null,
            'medium' => null,
            'large' => null
    ];

        try {
            if (!extension_loaded('gd') && !extension_loaded('imagick')) {
                return $paths;
            }

            $manager = new ImageManager(new Driver());

            $thumbnail = $manager->read($file)->scaleDown(width: 400);
            $thumbnailPath = 'units/thumbnails/thumb_' . $fileName;
            Storage::disk('public')->put($thumbnailPath, (string) $thumbnail->encode());
            $paths['thumbnail'] = $thumbnailPath;

            $medium = $manager->read($file)->scaleDown(width: 800);
            $mediumPath = 'units/medium/medium_' . $fileName;
            Storage::disk('public')->put($mediumPath, (string) $medium->encode());
            $paths['medium'] = $mediumPath;

            $large = $manager->read($file)->scaleDown(width: 1200);
            $largePath = 'units/large/large_' . $fileName;
            Storage::disk('public')->put($largePath, (string) $large->encode());
            $paths['large'] = $largePath;

        } catch (\Exception $e) {
            Log::error('Image generation failed: ' . $e->getMessage());
        }

        return $paths;
    }

    protected function deletePhotoFiles(UnitPhoto $photo): void
    {
        $disk = Storage::disk('public');
        
        $paths = [
            $photo->original_path,
            $photo->thumbnail_path,
            $photo->medium_path,
            $photo->large_path
    ];

        foreach ($paths as $path) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }
}