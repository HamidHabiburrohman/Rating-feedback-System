<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\Student\ReportSubmittedMail;
use App\Models\Report\Report;
use App\Models\Report\ReportCategory;
use App\Models\Feedback\Rating;
use Illuminate\Support\Facades\Cache;
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
        $studentId = auth('student')->id();

        if ($rating->student_id !== $studentId) {
            return false;
        }

        if ($rating->status !== 'active') {
            return false;
        }

        if ($rating->reports()->whereIn('status', ['new', 'in_progress'])->exists()) {
            return false;
        }

        $lastResolved = $rating->reports()->where('status', 'resolved')->latest()->first();
        if ($lastResolved && $lastResolved->updated_at->addDays(7) > now()) {
            return false;
        }

        return true;
    }

    public function getReportStatusMessage(Rating $rating): string
    {
        if ($rating->student_id !== auth('student')->id()) {
            return 'Anda tidak memiliki akses ke rating ini';
        }

        if ($rating->status !== 'active') {
            return 'Rating ini tidak dapat dilaporkan';
        }

        if ($rating->reports()->whereIn('status', ['new', 'in_progress'])->exists()) {
            return 'Anda sudah memiliki laporan yang sedang diproses untuk rating ini';
        }

        $lastResolved = $rating->reports()->where('status', 'resolved')->latest()->first();
        if ($lastResolved && $lastResolved->updated_at->addDays(7) > now()) {
            return 'Silakan tunggu 7 hari setelah laporan sebelumnya diselesaikan';
        }

        return '';
    }

    public function createReport(array $data, int $studentId): Report
    {
        return DB::transaction(function () use ($data, $studentId) {
            $rating = Rating::findOrFail($data['rating_id']);

            if (!$this->canReport($rating)) {
                throw new \Exception($this->getReportStatusMessage($rating));
            }

            $report = $this->report->create([
                'tracking_code' => 'RPT-' . strtoupper(uniqid()),
                'rating_id' => $rating->id,
                'unit_id' => $rating->unit_id,
                'student_id' => $studentId,
                'report_category_id' => ReportCategory::where('slug', $data['category'])->first()?->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? 'medium',
                'status' => 'new',
                'attachment_path' => null,
                'attachment_original_name' => null,
                'attachment_mime_type' => null,
                'attachment_size' => null,
            ]);

            if (!empty($data['attachment_path'])) {
                $report->update([
                    'attachment_path' => $data['attachment_path'],
                    'attachment_original_name' => $data['attachment_original_name'] ?? null,
                    'attachment_mime_type' => $data['attachment_mime_type'] ?? null,
                    'attachment_size' => $data['attachment_size'] ?? null,
                ]);
            }

            $student = Student::find($studentId);

            Mail::to($student->email)->send(new ReportSubmittedMail(
                $student->name,
                $rating->unit->name,
                $report->title,
                $report->tracking_code,
                $report->priority
            ));

            return $report->fresh(['rating', 'category', 'unit']);
        });
    }

    public function findByTrackingCode(string $trackingCode): Report
    {
        return $this->report
            ->with(['rating', 'category', 'unit', 'replies.employee', 'statusHistory.employee'])
            ->where('tracking_code', $trackingCode)
            ->firstOrFail();
    }

    public function getUserReports(int $studentId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->report
            ->with(['rating', 'category', 'unit'])
            ->where('student_id', $studentId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%")
                    ->orWhereHas('unit', fn($u) => $u->where('name', 'like', "%{$filters['search']}%"));
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    public function getReportStats(int $studentId): array
    {
        $cacheKey = "student_report_stats_{$studentId}";

        return Cache::tags(['reports', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId) {
            $reports = $this->report->where('student_id', $studentId);

            return [
                'total' => $reports->count(),
                'new' => $reports->where('status', 'new')->count(),
                'in_progress' => $reports->where('status', 'in_progress')->count(),
                'resolved' => $reports->where('status', 'resolved')->count(),
                'rejected' => $reports->where('status', 'rejected')->count()
    ];
        });
    }
}
