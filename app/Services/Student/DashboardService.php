<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use App\Models\Unit\Unit;
use Illuminate\Support\Facades\Cache;

class DashboardService extends BaseStudentService
{
    public function getStats(int $studentId): array
    {
        $cacheKey = "student_dashboard_stats_{$studentId}";

        return Cache::tags(['dashboard', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId) {
            $student = Student::findOrFail($studentId);

            return [
                'total_ratings' => $student->ratings()->count(),
                'total_reports' => $student->reports()->count(),
                'total_units_visited' => $student->visits()->distinct('unit_id')->count('unit_id'),
                'active_reports' => $student->reports()->whereIn('status', ['new', 'in_progress'])->count(),
            ];
        });
    }

    public function getActivityChart(int $studentId, int $days = 30): array
    {
        $cacheKey = "student_activity_chart_{$studentId}_{$days}";

        return Cache::tags(['dashboard', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId, $days) {
            $student = Student::findOrFail($studentId);
            $startDate = now()->subDays($days);

            $ratings = $student->ratings()
                ->where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $reports = $student->reports()
                ->where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $labels = collect(range(0, $days - 1))
                ->map(fn($i) => now()->subDays($days - 1 - $i)->format('Y-m-d'))
                ->toArray();

            return [
                'labels' => $labels,
                'ratings' => $ratings,
                'reports' => $reports,
            ];
        });
    }

    public function getRecommendedUnits(int $studentId, int $limit = 6): array
    {
        $cacheKey = "student_recommended_units_{$studentId}";

        return Cache::tags(['dashboard', "student_{$studentId}", 'units'])->remember($cacheKey, 600, function () use ($studentId, $limit) {
            $student = Student::findOrFail($studentId);

            // Get units yang sudah dikunjungi student
            $visitedUnitIds = $student->visits()
                ->pluck('unit_id')
                ->unique()
                ->toArray();

            // Get units yang sudah di-rate student
            $ratedUnitIds = $student->ratings()
                ->pluck('unit_id')
                ->unique()
                ->toArray();

            $excludeIds = array_unique(array_merge($visitedUnitIds, $ratedUnitIds));

            return Unit::with(['unitType', 'primaryPhoto'])
                ->where('is_active', true)
                ->whereNotIn('id', $excludeIds)
                ->where('avg_rating', '>', 0)
                ->orderByDesc('avg_rating')
                ->orderByDesc('total_ratings')
                ->limit($limit)
                ->get()
                ->map(function ($unit) {
                    return [
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'slug' => $unit->slug,
                        'type_name' => $unit->unitType?->name,
                        'avg_rating' => round($unit->avg_rating ?? 0, 1),
                        'total_ratings' => $unit->total_ratings ?? 0,
                        'primary_photo' => $unit->primaryPhoto,
                    ];
                })
                ->toArray();
        });
    }
}