<?php

declare(strict_types=1);

namespace App\Services\Admin\Conversation;

use App\Models\Conversation\Message;
use App\Models\Conversation\MessageAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class MessageAttachmentService
{
    private const ALLOWED_MIMES = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/png',
        'image/jpeg',
        'image/webp'
    ];

    private const MAX_SIZE = 10240; 

    private const DISK = 'public';
    private const DIRECTORY = 'messages/attachments';

    public function uploadMultiple(Message $message, array $files): array
    {
        $attachments = [];

        foreach ($files as $file) {
            $attachments[] = $this->upload($message, $file);
        }

        return $attachments;
    }

    public function upload(Message $message, UploadedFile $file): MessageAttachment
    {
        $this->validateAttachment($file);

        $storedName = uniqid('att_', true) . '.' . $file->getClientOriginalExtension();
        $path = $this->generateStoragePath($storedName);

        Storage::disk(self::DISK)->putFileAs(self::DIRECTORY, $file, $storedName);

        return MessageAttachment::create([
            'message_id' => $message->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'path' => $path,
        ]);
    }

    public function replace(MessageAttachment $attachment, UploadedFile $file): MessageAttachment
    {
        $this->validateAttachment($file);
        $this->deleteFile($attachment->path);

        $storedName = uniqid('att_', true) . '.' . $file->getClientOriginalExtension();
        $path = $this->generateStoragePath($storedName);

        Storage::disk(self::DISK)->putFileAs(self::DIRECTORY, $file, $storedName);

        $attachment->update([
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'path' => $path,
        ]);

        return $attachment;
    }

    public function delete(MessageAttachment $attachment): bool
    {
        $this->deleteFile($attachment->path);
        return (bool) $attachment->delete();
    }

    public function download(MessageAttachment $attachment): BinaryFileResponse
    {
        if (!Storage::disk(self::DISK)->exists($attachment->path)) {
            throw ValidationException::withMessages([
                'attachment' => ['File not found on storage.'],
            ]);
        }

        $fullPath = storage_path('app/public/' . $attachment->path);

        return response()->download($fullPath, $attachment->original_name);
    }

    public function preview(MessageAttachment $attachment): string
    {
        if (!Storage::disk(self::DISK)->exists($attachment->path)) {
            throw ValidationException::withMessages([
                'attachment' => ['File not found on storage.'],
            ]);
        }

        return asset('storage/' . $attachment->path);
    }

    public function removeOrphanFiles(): int
    {
        $deletedCount = 0;
        $storageFiles = Storage::disk(self::DISK)->files(self::DIRECTORY);
        
        $dbFiles = MessageAttachment::pluck('path')->toArray();

        foreach ($storageFiles as $file) {
            if (!in_array($file, $dbFiles, true)) {
                Storage::disk(self::DISK)->delete($file);
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    private function validateAttachment(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
            throw ValidationException::withMessages([
                'attachment' => ['Invalid file type. Allowed: PDF, DOCX, XLSX, PNG, JPG, WEBP.'],
            ]);
        }

        if ($file->getSize() > self::MAX_SIZE * 1024) {
            throw ValidationException::withMessages([
                'attachment' => ['File size exceeds the maximum limit of ' . self::MAX_SIZE . ' KB.'],
            ]);
        }
    }

    private function generateStoragePath(string $storedName): string
    {
        return self::DIRECTORY . '/' . $storedName;
    }

    private function deleteFile(string $path): void
    {
        if (Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}