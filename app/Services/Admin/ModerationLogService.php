<?php

namespace App\Services\Admin;

use App\Models\System\ModerationLog;
use App\Services\Admin\Shared\BaseAdminService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ModerationLogService extends BaseAdminService
{
    public function getFilteredLogs(array $filters = [])
    {
        $query = ModerationLog::with(['admin']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('admin', fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['action'])) $query->where('action', $filters['action']);
        if (!empty($filters['target_type'])) $query->where('target_type', $filters['target_type']);
        if (!empty($filters['admin_id'])) $query->where('admin_id', $filters['admin_id']);

        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getDetail(int $id): ModerationLog
    {
        return ModerationLog::with(['admin'])->findOrFail($id);
    }

    public function getStats(): array
    {
        return Cache::tags(['moderation_logs'])->remember('moderation_log_stats', 300, function () {
            return [
                'total' => ModerationLog::count(),
                'today' => ModerationLog::whereDate('created_at', today())->count(),
                'this_week' => ModerationLog::where('created_at', '>=', now()->startOfWeek())->count(),
                'by_action' => ModerationLog::selectRaw('action, COUNT(*) as count')->groupBy('action')->pluck('count', 'action')->toArray()
            ];
        });
    }

    public function getByTarget(string $targetType, int $targetId)
    {
        return ModerationLog::with(['admin'])
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->latest()
            ->get();
    }

    public function getByAdmin(int $adminId)
    {
        return ModerationLog::with(['admin'])
            ->where('admin_id', $adminId)
            ->latest()
            ->paginate(15);
    }

    public function getSummary(): array
    {
        return Cache::tags(['moderation_logs'])->remember('moderation_log_summary', 300, function () {
            $topAdmins = ModerationLog::select('admin_id', DB::raw('COUNT(*) as total'))
                ->groupBy('admin_id')
                ->orderByDesc('total')
                ->limit(5)
                ->with('admin:id,nama')
                ->get()
                ->toArray();

            return [
                'top_admins' => $topAdmins,
                'recent_actions' => ModerationLog::with(['admin'])->latest()->limit(10)->get()->toArray()
            ];
        });
    }

    public function export(array $filters = [])
    {
        $query = ModerationLog::with(['admin']);

        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);
        if (!empty($filters['action'])) $query->where('action', $filters['action']);

        $logs = $query->get();

        $rows = $logs->map(function ($log) {
            return [
                'ID' => $log->id,
                'Admin' => $log->admin?->nama ?? '-',
                'Action' => $log->action,
                'Target Type' => class_basename($log->target_type),
                'Target ID' => $log->target_id,
                'Old Value' => $log->old_value,
                'New Value' => $log->new_value,
                'Reason' => $log->reason ?? '-',
                'IP Address' => $log->ip_address,
                'Created At' => $log->created_at?->format('Y-m-d H:i:s')
            ];
        })->toArray();

        $headers = array_keys($rows[0] ?? []);
        $filename = 'moderation_logs_' . now()->format('Y-m-d_His');

        $exportManager = app(\App\Services\Export\ExportManager::class);
        return $exportManager->getExcelService()->export($rows, $headers, 'Moderation Log Export', $filename);
    }

    public function cleanup(int $days): int
    {
        return DB::transaction(function () use ($days) {
            $count = ModerationLog::where('created_at', '<', now()->subDays($days))->delete();
            Cache::tags(['moderation_logs'])->flush();
            return $count;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            ModerationLog::findOrFail($id)->delete();
            Cache::tags(['moderation_logs'])->flush();
            return true;
        });
    }

    public function bulkDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = ModerationLog::whereIn('id', $ids)->delete();
            Cache::tags(['moderation_logs'])->flush();
            return $count;
        });
    }
}
