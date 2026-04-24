<?php

namespace App\Services\Student;

use App\Models\Report;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class ReportService extends BaseStudentService
{
    protected Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function canReport(Rating $rating): bool
    {
        $studentIdentifier = auth('student')->user()->student_identifier;

        if ($rating->student_identifier !== $studentIdentifier) {
            return false;
        }

        if ($rating->status !== 'active') {
            return false;
        }

        $activeReport = $rating->activeReport()->first();
        if ($activeReport && in_array($activeReport->status, ['new', 'processing', 'under_review'])) {
            return false;
        }

        $lastReport = $rating->reports()
            ->where('status', 'resolved')
            ->latest()
            ->first();

        if ($lastReport && $lastReport->updated_at->addDays(7) > now()) {
            return false;
        }

        return true;
    }

    public function getReportStatusMessage(Rating $rating): string
    {
        $studentIdentifier = auth('student')->user()->student_identifier;

        if ($rating->student_identifier !== $studentIdentifier) {
            return 'Anda tidak memiliki akses ke rating ini';
        }

        if ($rating->status !== 'active') {
            return 'Hanya rating aktif yang dapat dilaporkan';
        }

        $activeReport = $rating->activeReport()->first();
        if ($activeReport && in_array($activeReport->status, ['new', 'processing', 'under_review'])) {
            return 'Laporan untuk rating ini sedang diproses oleh admin';
        }

        $lastReport = $rating->reports()
            ->where('status', 'resolved')
            ->latest()
            ->first();

        if ($lastReport) {
            $daysPassed = now()->diffInDays($lastReport->updated_at);
            $daysLeft = 7 - $daysPassed;
            if ($daysLeft > 0) {
                return "Tunggu {$daysLeft} hari lagi sebelum dapat melapor kembali";
            }
        }

        return 'Anda dapat melaporkan rating ini';
    }

    public function createReport(array $data, string $studentIdentifier): Report
    {
        return DB::transaction(function () use ($data, $studentIdentifier) {
            $rating = Rating::with('unit')->findOrFail($data['rating_id']);

            if (!$this->canReport($rating)) {
                throw new \Exception($this->getReportStatusMessage($rating));
            }

            $reportData = [
                'tracking_code' => 'RPT-' . strtoupper(uniqid()),
                'rating_id' => $rating->id,
                'unit_id' => $rating->unit_id,
                'student_identifier' => $studentIdentifier,
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? 'medium',
                'status' => 'new'
            ];

            if (isset($data['attachment']) && $data['attachment'] && $data['attachment']->isValid()) {
                $file = $data['attachment'];
                $path = $file->store('reports/' . date('Y/m/d'), 'public');

                $reportData['attachment_path'] = $path;
                $reportData['attachment_original_name'] = $file->getClientOriginalName();
                $reportData['attachment_mime_type'] = $file->getMimeType();
                $reportData['attachment_size'] = $file->getSize();
            }

            return $this->report->create($reportData);
        });
    }

    public function findByTrackingCode(string $trackingCode): Report
    {
        return $this->report->with(['unit', 'rating', 'admin'])
            ->where('tracking_code', $trackingCode)
            ->firstOrFail();
    }

    public function getUserReports(string $studentIdentifier, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->report->with(['unit'])
            ->where('student_identifier', $studentIdentifier);

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

        return $query->latest()->paginate($perPage);
    }

    public function getReportStats(string $studentIdentifier): array
    {
        $reports = $this->report->where('student_identifier', $studentIdentifier);

        return [
            'total' => $reports->count(),
            'new' => $reports->where('status', 'new')->count(),
            'processing' => $reports->where('status', 'processing')->count(),
            'resolved' => $reports->where('status', 'resolved')->count(),
            'rejected' => $reports->where('status', 'rejected')->count()
        ];
    }
}