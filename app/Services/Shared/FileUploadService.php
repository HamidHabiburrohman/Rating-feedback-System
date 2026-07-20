<?php

namespace App\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    protected string $disk = 'public';

    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp'
    ];

    protected int $maxSize = 5242880;

    public function setDisk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    public function upload(UploadedFile $file, string $path, ?string $fileName = null): ?string
    {
        $this->validate($file);

        $fileName = $fileName ?? $this->generateFileName($file);

        $fullPath = $file->storeAs($path, $fileName, $this->disk);

        if (!$fullPath) {
            return null;
        }

        return $fullPath;
    }

    public function uploadMultiple(array $files, string $path, int $maxFiles = 3): array
    {
        $uploaded = [];
        $count = 0;

        foreach ($files as $file) {
            if ($count >= $maxFiles) {
                break;
            }

            if ($file instanceof UploadedFile) {
                $result = $this->upload($file, $path);
                if ($result) {
                    $uploaded[] = [
                        'path' => $result,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'disk' => $this->disk
    ];
                    $count++;
                }
            }
        }

        return $uploaded;
    }

    public function delete(string $path): bool
    {
        if (!Storage::disk($this->disk)->exists($path)) {
            return false;
        }

        return Storage::disk($this->disk)->delete($path);
    }

    public function getUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    protected function validate(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('File type not allowed. Allowed: JPG, JPEG, PNG, WEBP');
        }

        if ($file->getSize() > $this->maxSize) {
            throw new \InvalidArgumentException('File size exceeds maximum of 5MB');
        }
    }

    protected function generateFileName(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    public function setMaxSize(int $bytes): self
    {
        $this->maxSize = $bytes;
        return $this;
    }
}