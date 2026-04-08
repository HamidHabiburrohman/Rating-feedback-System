<?php

namespace App\Services\Student;

use App\Models\Rating;
use App\Models\Report;
use App\Models\Unit;
use App\Models\Student;
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

    /**
     * Get statistics for student dashboard
     * IMPORTANT: Parameter $studentId adalah ID dari tabel students (auto increment)
     * Tapi query ke ratings/reports harus pakai student_identifier!
     */
    public function getStats(int $studentId): array
    {
        // Step 1: Ambil data student berdasarkan ID untuk mendapatkan student_identifier
        $student = Student::find($studentId);
        
        if (!$student) {
            return [
                'total_ratings' => 0,
                'total_reports' => 0,
                'active_reports' => 0,
                'average_rating' => 0,
                'unique_units_rated' => 0
            ];
        }
        
        // Step 2: Query ratings menggunakan student_identifier (bukan student_id!)
        // karena di migration ratings menggunakan $table->string('student_identifier')
        $ratingStats = $this->rating
            ->where('student_identifier', $student->student_identifier)
            ->selectRaw('COUNT(*) as total, AVG(overall_score) as avg, COUNT(DISTINCT unit_id) as units')
            ->first();

        // Step 3: Query reports juga pakai student_identifier
        $reportStats = $this->report
            ->where('student_identifier', $student->student_identifier)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status IN ('new','in_progress') THEN 1 ELSE 0 END) as active
            ")
            ->first();

        return [
            'total_ratings' => (int) ($ratingStats->total ?? 0),
            'total_reports' => (int) ($reportStats->total ?? 0),
            'active_reports' => (int) ($reportStats->active ?? 0),
            'average_rating' => round($ratingStats->avg ?? 0, 2),
            'unique_units_rated' => (int) ($ratingStats->units ?? 0)
        ];
    }

    /**
     * Get recent ratings for student
     * NOTE: Query pakai student_identifier, bukan ID
     */
    public function getRecentRatings(int $studentId, int $limit = 5): array
    {
        $student = Student::find($studentId);
        
        if (!$student) {
            return [];
        }
        
        return $this->rating->with('unit:id,name')
            ->where('student_identifier', $student->student_identifier)
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

    /**
     * Get recent reports for student
     * NOTE: Query pakai student_identifier, bukan ID
     */
    public function getRecentReports(int $studentId, int $limit = 5): array
    {
        $student = Student::find($studentId);
        
        if (!$student) {
            return [];
        }
        
        return $this->report->with('unit:id,name')
            ->where('student_identifier', $student->student_identifier)
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

    /**
     * Get recommended units that student hasn't rated yet
     * NOTE: Query ini pakai ID langsung karena unit_id di ratings adalah ID, bukan identifier
     */
    public function getRecommendedUnits(int $studentId, int $limit = 6): array
    {
        $student = Student::find($studentId);
        
        if (!$student) {
            return [];
        }
        
        // Ambil daftar unit_id yang sudah dirating oleh student ini
        // Pakai student_identifier untuk filter di tabel ratings
        $ratedUnitIds = $this->rating
            ->where('student_identifier', $student->student_identifier)
            ->pluck('unit_id');

        return $this->unit->with('type:id,name')
            ->where('is_active', true)
            ->whereNotIn('id', $ratedUnitIds)
            ->orderByDesc('avg_rating')
            ->limit($limit)
            ->get()
            ->map(fn($unit) => [
                'id' => $unit->id,
                'name' => $unit->name,
                'slug' => $unit->slug,
                'type' => $unit->type?->name ?? 'General',
                'avg_rating' => $unit->avg_rating,
                'total_ratings' => $unit->total_ratings,
                'location' => $unit->location
            ])
            ->toArray();
    }

    /**
     * Get activity chart data for student ratings
     */
    public function getActivityChart(int $studentId, string $period = 'week'): array
    {
        $student = Student::find($studentId);
        
        if (!$student) {
            return ['labels' => [], 'data' => []];
        }
        
        $days = $period === 'month' ? 30 : 7;

        $data = $this->rating
            ->where('student_identifier', $student->student_identifier)
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