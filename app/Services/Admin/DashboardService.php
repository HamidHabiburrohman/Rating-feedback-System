<?php

namespace App\Services\Admin;

use App\Models\Authentication\Student;
use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Feedback\Rating;
use App\Models\Report\Report;
use App\Models\System\ModerationLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseAdminService
{
    public function getStats(): array
    {
        return Cache::tags(['dashboard', 'admin'])->remember('admin_dashboard_stats', 120, function () {
            return [
                'total_units' => Unit::count(),
                'active_units' => Unit::where('is_active', true)->count(),
                'total_students' => Student::count(),
                'total_employees' => Employee::count(),
                'total_ratings' => Rating::count(),
                'total_reports' => Report::count(),
                'pending_reports' => Report::where('status', 'new')->count(),
                'avg_rating' => round(Rating::avg('overall_score') ?? 0, 2),
            ];
        });
    }

    public function getRecentRatings(int $limit = 5): array
    {
        return Cache::tags(['dashboard', 'ratings'])->remember('admin_recent_ratings', 180, function () use ($limit) {
            return Rating::with(['student', 'unit'])
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getRecentReports(int $limit = 5): array
    {
        return Cache::tags(['dashboard', 'reports'])->remember('admin_recent_reports', 180, function () use ($limit) {
            return Report::with(['student', 'unit', 'category'])
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getChartData(string $period = 'week'): array
    {
        $cacheKey = "admin_chart_{$period}";
        return Cache::tags(['dashboard'])->remember($cacheKey, 300, function () use ($period) {
            $days = $period === 'month' ? 30 : 7;
            $start = now()->subDays($days);

            $ratings = Rating::where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $reports = Report::where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            return [
                'labels' => collect(range(0, $days - 1))->map(fn($i) => now()->subDays($days - 1 - $i)->format('Y-m-d'))->toArray(),
                'ratings' => $ratings,
                'reports' => $reports,
            ];
        });
    }

    public function getOverview(): array
    {
        return Cache::tags(['dashboard'])->remember('admin_overview', 300, function () {
            return [
                'rating_distribution' => Rating::selectRaw('FLOOR(overall_score) as score, COUNT(*) as count')
                    ->groupBy('score')
                    ->pluck('count', 'score')
                    ->toArray(),
                'report_status' => Report::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
            ];
        });
    }

    public function getAuditLogs(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = ModerationLog::with(['admin', 'target']);

        if (!empty($filters['action'])) $query->where('action', $filters['action']);
        if (!empty($filters['target_type'])) $query->where('target_type', $filters['target_type']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function getRecentRated(int $limit = 10): array
    {
        return Cache::tags(['dashboard'])->remember('admin_recent_rated_units', 300, function () use ($limit) {
            return Unit::with(['type', 'primaryPhoto'])
                ->where('is_active', true)
                ->where('total_ratings', '>', 0)
                ->orderByDesc('updated_at')
                ->limit($limit)
                ->get(['id', 'name', 'slug', 'total_ratings', 'avg_rating', 'unit_type_id', 'primary_photo_id'])
                ->toArray();
        });
    }

    public function getTopUnits(string $type = 'all', int $limit = 5): array
    {
        $cacheKey = "admin_top_units_{$type}_{$limit}";
        return Cache::tags(['dashboard', 'units'])->remember($cacheKey, 300, function () use ($type, $limit) {
            $query = Unit::with(['type', 'primaryPhoto'])
                ->where('is_active', true)
                ->where('avg_rating', '>', 0);

            if ($type !== 'all') {
                $query->whereHas('type', fn($q) => $q->where('slug', $type));
            }

            return $query->orderByDesc('avg_rating')
                ->orderByDesc('total_ratings')
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getAttentionUnits(): array
    {
        return Cache::tags(['dashboard', 'units'])->remember('admin_attention_units', 300, function () {
            return Unit::with(['type', 'primaryPhoto'])
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->where('avg_rating', '<', 2.5)
                      ->orWhere('total_ratings', 0)
                      ->orWhereHas('reports', fn($r) => $r->where('status', 'new'));
                })
                ->limit(5)
                ->get()
                ->toArray();
        });
    }
}