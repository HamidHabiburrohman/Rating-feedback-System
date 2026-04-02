<?php

namespace App\Observers\Admin;

use App\Models\ModerationLog;
use App\Models\UnitPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UnitPhotoObserver
{
    public function created(UnitPhoto $photo): void
    {
        $this->logActivity('created', $photo);
    }

    public function updated(UnitPhoto $photo): void
    {
        if ($photo->isDirty('is_primary')) {
            $this->logActivity('primary_changed', $photo, [
                'old' => $photo->getOriginal('is_primary'),
                'new' => $photo->is_primary
            ]);
        }
    }

    public function deleted(UnitPhoto $photo): void
    {
        $this->logActivity('deleted', $photo);
    }

    protected function logActivity(string $action, UnitPhoto $photo, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'unit_photo_' . $action,
                'target_type' => 'unit_photo',
                'target_id' => $photo->id,
                'metadata' => json_encode(array_merge([
                    'unit_id' => $photo->unit_id,
                    'file_name' => $photo->file_name,
                    'is_primary' => $photo->is_primary,
                ], $additional)),
            ]);

            Log::info('UnitPhoto ' . $action, [
                'photo_id' => $photo->id,
                'unit_id' => $photo->unit_id,
                'admin_id' => Auth::id(),
                'additional' => $additional
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log photo activity: ' . $e->getMessage());
        }
    }
}