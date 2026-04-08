<?php

namespace App\Services\Admin;

use App\Models\Unit;
use App\Models\UnitPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UnitPhotoService
{
    protected UnitPhoto $unitPhoto;

    public function __construct(UnitPhoto $unitPhoto)
    {
        $this->unitPhoto = $unitPhoto;
    }

    public function validatePhotos(array $photos): array
    {
        $errors = [];
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024;

        foreach ($photos as $index => $photo) {
            if (!in_array($photo->getMimeType(), $allowedMimes)) {
                $errors[] = "File ke-" . ($index + 1) . " harus berupa gambar (JPEG/PNG)";
            }

            if ($photo->getSize() > $maxSize) {
                $errors[] = "File ke-" . ($index + 1) . " maksimal 5MB";
            }
        }

        return $errors;
    }

    public function upload(Unit $unit, array $photos, ?bool $setAsPrimary = false): array
    {
        try {
            $uploaded = [];
            $hasPrimary = $unit->photos()->where('is_primary', true)->exists();
            $validationErrors = $this->validatePhotos($photos);

            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => implode(', ', $validationErrors),
                    'errors' => $validationErrors
                ];
            }

            foreach ($photos as $index => $photo) {
                $path = $photo->store("units/{$unit->id}", 'public');

                $photoData = [
                    'unit_id' => $unit->id,
                    'uploaded_by_admin_id' => Auth::id(),
                    'original_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'file_size' => $photo->getSize(),
                    'sort_order' => $unit->photos()->count() + $index + 1,
                    'is_primary' => (!$hasPrimary && $index === 0) || ($setAsPrimary && $index === 0)
                ];

                $uploaded[] = $this->unitPhoto->create($photoData);
            }

            $this->logAdminAction('upload_photos', $unit, null, ['count' => count($photos)]);

            return [
                'success' => true,
                'message' => count($uploaded) . ' foto berhasil diupload',
                'data' => $uploaded
            ];
        } catch (\Exception $e) {
            Log::error('Upload photo error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat upload foto: ' . $e->getMessage()
            ];
        }
    }

    public function setPrimary(Unit $unit, int $photoId): array
    {
        try {
            $photo = $this->unitPhoto->where('unit_id', $unit->id)->find($photoId);

            if (!$photo) {
                return [
                    'success' => false,
                    'message' => 'Foto tidak ditemukan'
                ];
            }

            $this->unitPhoto->where('unit_id', $unit->id)->update(['is_primary' => false]);

            $photo->is_primary = true;
            $photo->save();

            $this->logAdminAction('set_primary_photo', $photo, null, ['photo_id' => $photoId]);

            return [
                'success' => true,
                'message' => 'Foto berhasil dijadikan sebagai foto utama'
            ];
        } catch (\Exception $e) {
            Log::error('Set primary photo error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function reorder(Unit $unit, array $photoIds): array
    {
        try {
            foreach ($photoIds as $index => $id) {
                $this->unitPhoto->where('unit_id', $unit->id)
                    ->where('id', $id)
                    ->update(['sort_order' => $index + 1]);
            }

            $this->logAdminAction('reorder_photos', $unit, null, ['count' => count($photoIds)]);

            return [
                'success' => true,
                'message' => 'Urutan foto berhasil diubah'
            ];
        } catch (\Exception $e) {
            Log::error('Reorder photos error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function deletePhoto(UnitPhoto $photo): array
    {
        try {
            $unitId = $photo->unit_id;
            $wasPrimary = $photo->is_primary;

            if ($photo->original_path && Storage::disk('public')->exists($photo->original_path)) {
                Storage::disk('public')->delete($photo->original_path);
            }

            $result = $photo->delete();

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus foto'
                ];
            }

            if ($wasPrimary) {
                $newPrimary = $this->unitPhoto->where('unit_id', $unitId)
                    ->orderBy('sort_order')
                    ->first();

                if ($newPrimary) {
                    $newPrimary->is_primary = true;
                    $newPrimary->save();
                }
            }

            $unit = Unit::find($unitId);
            if ($unit) {
                $this->logAdminAction('delete_photo', $unit, null, [
                    'photo_id' => $photo->id,
                    'was_primary' => $wasPrimary
                ]);
            }

            return [
                'success' => true,
                'message' => 'Foto berhasil dihapus'
            ];
        } catch (\Exception $e) {
            Log::error('Delete photo error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    protected function getAdminId(): ?int
    {
        return Auth::id();
    }

    protected function logAdminAction(string $action, $target, $reason = null, array $metadata = [])
    {
        if (class_exists('\App\Models\ModerationLog')) {
            \App\Models\ModerationLog::log(
                $this->getAdminId(),
                $action,
                $target,
                $reason,
                $metadata
            );
        }
    }
}