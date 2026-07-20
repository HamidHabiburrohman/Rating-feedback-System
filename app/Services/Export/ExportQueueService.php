<?php

namespace App\Services\Export;

use App\Jobs\ProcessExportJob;
use App\Models\Core\User;
use App\Models\Export\ExportLog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ExportQueueService
{
    public function queueExport(User $user, string $type, array $filters, string $format): ExportLog
    {
        $exportLog = ExportLog::create([
            'user_id' => $user->id,
            'export_type' => $type,
            'format' => $format,
            'filters' => $filters,
            'status' => 'queued',
            'total_records' => 0,
        ]);

        ProcessExportJob::dispatch($exportLog)->onQueue('exports');

        return $exportLog;
    }

    public function getStatus($exportId): array
    {
        $exportLog = ExportLog::findOrFail($exportId);

        return [
            'id' => $exportLog->id,
            'type' => $exportLog->export_type,
            'format' => $exportLog->format,
            'status' => $exportLog->status,
            'filename' => $exportLog->filename,
            'file_path' => $exportLog->file_path,
            'total_records' => $exportLog->total_records,
            'created_at' => $exportLog->created_at,
            'completed_at' => $exportLog->completed_at,
            'error_message' => $exportLog->error_message,
            'download_url' => $exportLog->status === 'completed' && $exportLog->file_path 
                ? route('admin.exports.download', $exportLog->id) 
                : null
    ];
    }

    public function getUserExports(User $user, $status = null, $limit = 50)
    {
        $query = ExportLog::where('user_id', $user->id)->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->limit($limit)->get();
    }

    public function getRecentExports(User $user, $days = 7)
    {
        return ExportLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function cancelExport($exportId): bool
    {
        $exportLog = ExportLog::find($exportId);

        if (!$exportLog || !in_array($exportLog->status, ['queued', 'processing'])) {
            return false;
        }

        $exportLog->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return true;
    }

    public function deleteExport($exportId): bool
    {
        $exportLog = ExportLog::find($exportId);

        if (!$exportLog || $exportLog->user_id !== auth()->id()) {
            return false;
        }

        if ($exportLog->file_path && Storage::exists($exportLog->file_path)) {
            Storage::delete($exportLog->file_path);
        }

        return $exportLog->delete();
    }

    public function cleanupExpiredExports($days = 3): int
    {
        $expiredDate = Carbon::now()->subDays($days);

        $exports = ExportLog::where('created_at', '<', $expiredDate)
            ->where('status', 'completed')
            ->get();

        $deleted = 0;

        foreach ($exports as $export) {
            if ($export->file_path && Storage::exists($export->file_path)) {
                Storage::delete($export->file_path);
            }
            
            $export->delete();
            $deleted++;
        }

        return $deleted;
    }

    public function retryFailedExport($exportId): bool
    {
        $exportLog = ExportLog::find($exportId);

        if (!$exportLog || $exportLog->status !== 'failed') {
            return false;
        }

        $exportLog->update([
            'status' => 'queued',
            'error_message' => null,
        ]);

        ProcessExportJob::dispatch($exportLog)->onQueue('exports');

        return true;
    }
}