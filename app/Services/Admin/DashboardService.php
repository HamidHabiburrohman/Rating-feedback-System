<?php

namespace App\Services\Admin;

use App\Models\Authentication\Student;
use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Feedback\Rating;
use App\Models\Report\Report;
use App\Models\System\ModerationLog;
use Illuminate\Support\Facades\Cache;

class DashboardService extends BaseAdminService
{
    public function getStats(): array
    {
        return Cache::tags(['dashboard', 'admin'])->remember('admin_dashboard_stats', 120, function () {
            $totalStudents = Student::where('is_active', true)->count();
            $totalRatings = Rating::whereIn('status', ['active', 'edited'])->count();
            $activeUnits = Unit::where('is_active', true)->count();
            $totalEmployees = Employee::count();
            $activeEmployees = Employee::where('is_active', true)->count();

            $yesterday = now()->subDay()->startOfDay();
            $studentsToday = Student::whereDate('created_at', today())->count();
            $studentsYesterday = Student::whereDate('created_at', $yesterday->toDateString())->count();
            $studentTrend = $studentsYesterday > 0 ? round((($studentsToday - $studentsYesterday) / $studentsYesterday) * 100, 1) : 0;

            $ratingsToday = Rating::whereDate('created_at', today())->count();
            $ratingsYesterday = Rating::whereDate('created_at', $yesterday->toDateString())->count();
            $ratingTrend = $ratingsYesterday > 0 ? round((($ratingsToday - $ratingsYesterday) / $ratingsYesterday) * 100, 1) : 0;

            $avgRating = round(Rating::whereIn('status', ['active', 'edited'])->avg('overall_score') ?? 0, 1);

            return [
                'students' => [
                    'today' => $studentsToday,
                    'this_week' => Student::where('created_at', '>=', now()->startOfWeek())->count(),
                    'trend' => ['daily' => $studentTrend, 'weekly' => 0],
                ],
                'ratings' => [
                    'today' => $ratingsToday,
                    'total' => $totalRatings,
                    'trend' => $ratingTrend,
                ],
                'avg_rating' => $avgRating,
                'active_units' => $activeUnits,
                'total_units' => Unit::count(),
                'total_employees' => $totalEmployees,
                'active_employees' => $activeEmployees,
            ];
        });
    }

        public function getChartData(string $period = 'week'): array
    {
        $cacheKey = "admin_chart_dummy_{$period}";
        
        return Cache::tags(['dashboard'])->remember($cacheKey, 60, function () use ($period) {
            $days = match ($period) {
                'month' => 30,
                'year' => 365,
                default => 7,
            };

            $labels = [];
            $studentData = [];
            $newUnits = [];
            $cumulativeUnits = [];
            
            $baseTotalUnits = rand(450, 800);

            for ($i = $days - 1; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->format('M d');
                
                $studentData[] = rand(1200, 3500    );
                
                $dayNewUnits = ($i % 3000 === 0) ? rand(100, 140) : rand(300, 200);
                $newUnits[] = $dayNewUnits;
                
                $baseTotalUnits += $dayNewUnits;
                $cumulativeUnits[] = $baseTotalUnits;
            }

            return [
                'student_login_trend' => array_map(
                    fn($label, $students) => ['day' => $label, 'students' => $students],
                    $labels,
                    $studentData
                ),
                'unit_growth' => [
                    'months' => $labels,
                    'new_units' => $newUnits,
                    'cumulative' => $cumulativeUnits,
                ],
            ];
        });
    }

    public function getTopUnits(string $type = 'all', int $limit = 50): array
    {
        $cacheKey = "admin_top_units_{$type}_{$limit}";
        return Cache::tags(['dashboard', 'units'])->remember($cacheKey, 300, function () use ($type, $limit) {
            $query = Unit::with(['unitType', 'primaryPhoto'])
                ->where('is_active', true);

            if ($type === 'popularity') {
                $query->orderByDesc('total_ratings')->orderByDesc('avg_rating');
            } elseif ($type === 'quality') {
                $query->where('avg_rating', '>', 0)
                    ->orderByDesc('avg_rating')
                    ->orderByDesc('total_ratings');
            } else {
                $query->orderByDesc('total_ratings')->orderByDesc('avg_rating');
            }

            return $query->limit($limit)
                ->get()
                ->map(function ($unit) {
                    return [
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'avg_rating' => $unit->avg_rating ? round($unit->avg_rating, 1) : 0,
                        'total_ratings' => $unit->total_ratings ?? 0,
                        'operational_status' => $unit->operational_status ?? 'open',
                        'type' => ['name' => $unit->unitType?->name ?? 'General'],
                    ];
                })
                ->toArray();
        });
    }

    public function getAttentionUnits(int $limit = 50): array
    {
        return Cache::tags(['dashboard', 'units'])->remember('admin_attention_units_' . $limit, 300, function () use ($limit) {
            return Unit::with(['unitType', 'reports'])
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->where('avg_rating', '<', 2.5)
                        ->orWhere('total_ratings', 0)
                        ->orWhereHas('reports', fn($r) => $r->where('status', 'new'));
                })
                ->limit($limit)
                ->get()
                ->map(function ($unit) {
                    return [
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'avg_rating' => $unit->avg_rating ? round($unit->avg_rating, 1) : 0,
                        'total_ratings' => $unit->total_ratings ?? 0,
                        'operational_status' => $unit->operational_status ?? 'open',
                        'type' => ['name' => $unit->unitType?->name ?? 'General'],
                    ];
                })
                ->toArray();
        });
    }

    public function getRecentRated(int $limit = 10): array
    {
        return Cache::tags(['dashboard', 'ratings'])->remember('admin_recent_rated_units_' . $limit, 300, function () use ($limit) {
            return Rating::with(['student', 'unit.unitType'])
                ->whereIn('status', ['active', 'edited'])
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($rating) {
                    return [
                        'id' => $rating->id,
                        'tracking_code' => $rating->tracking_code,
                        'student_name' => $rating->student?->name ?? 'Anonymous',
                        'unit_name' => $rating->unit?->name ?? 'Unknown Unit',
                        'unit_type' => $rating->unit?->unitType?->name ?? 'General',
                        'overall_score' => round($rating->overall_score, 1),
                        'comment_preview' => $rating->comment ? \Illuminate\Support\Str::limit($rating->comment, 60) : null,
                        'time_ago' => $rating->created_at->diffForHumans(),
                        'created_at' => $rating->created_at->toISOString(),
                    ];
                })
                ->toArray();
        });
    }

    public function getTopEmployees(string $filter = 'all', int $limit = 5): array
    {
        $query = Employee::with(['unitAssignments.unit']);

        if ($filter === 'active') {
            $query->where('is_active', true);
        } elseif ($filter === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->latest()->limit($limit)->get()->map(function ($emp) {
            $units = $emp->unitAssignments->where('is_active', true)->pluck('unit.name')->filter()->values();
            return [
                'id' => $emp->id,
                'name' => $emp->name,
                'position' => $emp->position ?? 'Staff',
                'assigned_units' => $units->toArray(),
                'status' => $emp->is_active ? 'active' : 'inactive',
            ];
        })->toArray();
    }

    public function getAuditLogs(array $filters = []): array
    {
        $query = ModerationLog::with(['admin']);
        if (!empty($filters['action'])) $query->where('action', $filters['action']);
        if (!empty($filters['target_type'])) $query->where('target_type', $filters['target_type']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        return $query->latest()
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'user_name' => $log->admin?->nama ?? ($log->admin?->name ?? 'System'),
                    'action' => $log->action,
                    'description' => $log->description ?? $log->action,
                    'time_ago' => $log->created_at->diffForHumans(),
                ];
            })
            ->toArray();
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
}
