<?php

namespace App\Services\Student;

use App\Models\Report;
use App\Models\Rating;

class ReportService extends BaseStudentService
{
    protected Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function canReport(Rating $rating): bool
    {
        $studentId = auth('student')->id();

        if ($rating->student_id !== $studentId) {
            return false;
        }

        if ($rating->status === 'archived') {
            return false;
        }

        if ($rating->activeReport()->exists()) {
            return false;
        }

        $lastReport = $rating->report()->where('status', 'resolved')->latest()->first();

        if ($lastReport && $lastReport->updated_at->addDays(7) > now()) {
            return false;
        }

        return true;
    }

    public function getReportStatusMessage(Rating $rating): string
    {
        $studentId = auth('student')->id();

        if ($rating->student_id !== $studentId) {
            return 'Anda tidak memiliki akses ke rating ini';
        }

        if ($rating->status === 'archived') {
            return 'Rating ini telah diarsipkan';
        }

        if ($rating->activeReport()->exists()) {
            return 'Laporan untuk rating ini sedang diproses';
        }

        $lastReport = $rating->report()->where('status', 'resolved')->latest()->first();

        if ($lastReport) {
            $daysPassed = now()->diffInDays($lastReport->updated_at);
            $daysLeft = 7 - $daysPassed;
            if ($daysLeft > 0) {
                return "Tunggu {$daysLeft} hari lagi sebelum dapat melapor kembali";
            }
        }

        return 'Anda dapat melaporkan rating ini';
    }

    public function createReport(array $data, int $studentId): Report
    {
        $rating = Rating::with('unit')->findOrFail($data['rating_id']);

        if (!$this->canReport($rating)) {
            throw new \Exception('Tidak dapat melaporkan rating ini');
        }

        return $this->report->create([
            'tracking_code' => 'RPT-' . strtoupper(uniqid()),
            'rating_id' => $rating->id,
            'unit_id' => $rating->unit_id,
            'student_id' => $studentId,
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => 'medium',
            'status' => 'new'
        ]);
    }

    public function findByTrackingCode(string $trackingCode): Report
    {
        return $this->report->with(['unit', 'rating', 'admin'])
            ->where('tracking_code', $trackingCode)
            ->firstOrFail();
    }

    public function getUserReports(int $studentId, array $filters = []): array
    {
        $query = $this->report->with(['unit'])
            ->where('student_id', $studentId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('tracking_code', 'LIKE', "%{$filters['search']}%");
            });
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->latest()
            ->paginate($perPage)
            ->toArray();
    }
}