<?php

namespace App\Services\Admin;

use App\Models\ModerationLog;
use App\Models\User;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ModerationLogService extends BaseAdminService
{
    protected array $searchableColumns = ['reason', 'action'];
    protected array $filterableColumns = ['action', 'admin_id', 'target_type'];

    public function __construct(ModerationLog $moderationLog)
    {
        $this->model = $moderationLog;
    }

    public function getPaginatedLogs(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters, ['admin']);

            if (!empty($filters['target_type'])) {
                $query->where('target_type', $filters['target_type']);
            }

            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('ModerationLogService::getPaginatedLogs error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            
            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? 10,
                1,
                ['path' => request()->url()]
            );
        }
    }

    public function getLogDetail(int $id): ModerationLog
    {
        return $this->model->with(['admin'])->findOrFail($id);
    }

    public function getFilterData(): array
    {
        return [
            'actions' => $this->model->distinct()->pluck('action')->filter()->values(),
            'target_types' => $this->model->distinct()->pluck('target_type')->filter()->values(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->orderBy('nama')->get(['id', 'nama'])
        ];
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'today' => $this->model->whereDate('created_at', today())->count(),
            'this_week' => $this->model->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => $this->model->whereMonth('created_at', now()->month)->count(),
            'by_action' => $this->model->selectRaw('action, count(*) as total')
                ->groupBy('action')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),
            'by_admin' => $this->model->selectRaw('admin_id, count(*) as total')
                ->with('admin:id,nama')
                ->groupBy('admin_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'admin' => $item->admin?->nama ?? 'System',
                    'total' => $item->total
                ])
        ];
    }

    public function getLogsByTarget(string $targetType, int $targetId, int $limit = 50): array
    {
        return $this->model->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->with('admin')
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getActionsByAdmin(int $adminId, int $limit = 50): array
    {
        return $this->model->where('admin_id', $adminId)
            ->with('admin')
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getSummaryByDateRange(string $startDate, string $endDate): array
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, action, count(*) as total')
            ->groupBy('date', 'action')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(fn($items) => $items->pluck('total', 'action'))
            ->toArray();
    }

    public function cleanupOldLogs(int $days = 90): int
    {
        $cutoffDate = now()->subDays($days);
        
        $count = $this->model->where('created_at', '<', $cutoffDate)->delete();

        $this->logAdminAction('cleanup_logs', (object)['count' => $count, 'days' => $days]);

        return $count;
    }

    public function export(array $filters = [])
    {
        $query = $this->model->with('admin');

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['admin_id'])) {
            $query->where('admin_id', $filters['admin_id']);
        }

        if (!empty($filters['target_type'])) {
            $query->where('target_type', $filters['target_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $format = $filters['format'] ?? 'csv';

        if ($format === 'csv') {
            return $this->exportCsv($logs);
        }

        return $this->exportCsv($logs);
    }

    protected function exportCsv($logs)
    {
        $filename = 'moderation-logs-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Admin', 'Aksi', 'Tipe Target', 'ID Target', 'Alasan', 'Waktu']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->admin?->name ?? 'System',
                    $log->action,
                    $log->target_type,
                    $log->target_id,
                    $log->reason ?? '-',
                    $log->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}