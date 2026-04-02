<?php

namespace App\Services\Student;

use App\Models\Rating;
use App\Models\Report;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseStudentService
{
    protected Rating $rating;
    protected Report $report;
    protected Unit $unit;

    public function __construct(Rating $rating, Report $report, Unit $unit)
    {
        $this->rating = $rating;
        $this->report = $report;
        $this->unit = $unit;
    }

    public function getStats(int $studentId): array
    {
        $ratingStats = $this->rating
            ->where('student_id', $studentId)
            ->selectRaw('COUNT(*) as total, AVG(overall_score) as avg, COUNT(DISTINCT unit_id) as units')
            ->first();

        $reportStats = $this->report
            ->where('student_id', $studentId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status IN ('new','in_progress') THEN 1 ELSE 0 END) as active
            ")
            ->first();

        return [
            'total_ratings' => (int) $ratingStats->total,
            'total_reports' => (int) $reportStats->total,
            'active_reports' => (int) $reportStats->active,
            'average_rating' => round($ratingStats->avg ?? 0, 2),
            'unique_units_rated' => (int) $ratingStats->units
        ];
    }

    public function getRecentRatings(int $studentId, int $limit = 5): array
    {
        return $this->rating->with('unit:id,name')
            ->where('student_id', $studentId)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($rating) => [
                'tracking_code' => $rating->tracking_code,
                'unit_name' => $rating->unit?->name ?? 'Unknown',
                'overall_score' => $rating->overall_score,
                'status' => $rating->status,
                'created_at' => $rating->created_at->diffForHumans()
            ])
            ->toArray();
    }

    public function getRecentReports(int $studentId, int $limit = 5): array
    {
        return $this->report->with('unit:id,name')
            ->where('student_id', $studentId)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($report) => [
                'tracking_code' => $report->tracking_code,
                'unit_name' => $report->unit?->name ?? 'Unknown',
                'title' => $report->title,
                'status' => $report->status,
                'priority' => $report->priority,
                'created_at' => $report->created_at->diffForHumans()
            ])
            ->toArray();
    }

    public function getRecommendedUnits(int $studentId, int $limit = 6): array
    {
        $ratedUnitIds = $this->rating->where('student_id', $studentId)
            ->pluck('unit_id');

        return $this->unit->with('type:id,name')
            ->where('is_active', true)
            ->whereNotIn('id', $ratedUnitIds)
            ->orderByDesc('average_rating')
            ->limit($limit)
            ->get()
            ->map(fn($unit) => [
                'id' => $unit->id,
                'name' => $unit->name,
                'slug' => $unit->slug,
                'type' => $unit->type?->name ?? 'General',
                'avg_rating' => $unit->average_rating,
                'total_ratings' => $unit->total_ratings,
                'location' => $unit->location
            ])
            ->toArray();
    }

    public function getActivityChart(int $studentId, string $period = 'week'): array
    {
        $days = $period === 'month' ? 30 : 7;

        $data = $this->rating
            ->where('student_id', $studentId)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $values = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = $period === 'month'
                ? ($i % 5 === 0 ? now()->subDays($i)->format('d M') : '')
                : now()->subDays($i)->format('D');

            $values[] = $data[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $values
        ];
    }
}