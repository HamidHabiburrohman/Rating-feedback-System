<?php

namespace App\Services\Admin\Report;

use App\Models\Authentication\Admin;
use App\Models\Report\Report;
use App\Models\Report\ReportReply;
use App\Models\Report\ReportStatusHistory;
use App\Models\System\ModerationLog;
use App\Services\Admin\Shared\BaseAdminService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Student\ReportStatusChangedMail;
use Illuminate\Support\Facades\Log;
class ReportManagementService extends BaseAdminService
{
    protected array $validStatuses = ['new', 'assigned', 'in_progress', 'replied', 'resolved', 'rejected', 'pending_preview'];

    public function getFilteredReports(array $filters = [])
    {
        $query = Report::with(['student', 'unit', 'category', 'rating']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('tracking_code', 'like', "%{$search}%")
                    ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('unit', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['unit_id'])) $query->where('unit_id', $filters['unit_id']);

        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'priority':
                $query->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')");
                break;
            default:
                $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getDetail(int $id): array
    {
        $cacheKey = "admin_report_detail_{$id}";

        return Cache::tags(['reports', "report_{$id}"])->remember($cacheKey, 300, function () use ($id) {
            $report = Report::with([
                'student',
                'unit',
                'category',
                'rating',
                'replies.admin',
                'replies.employee',
                'statusHistory.admin',
                'statusHistory.employee',
                'attachments'
            ])->findOrFail($id);

            return [
                'report' => $report,
                'moderation_logs' => ModerationLog::where('target_type', Report::class)
                    ->where('target_id', $id)
                    ->with('admin')
                    ->latest()
                    ->get()
    ];
        });
    }

    public function updateStatus(int $id, string $status, ?string $reason, int $adminId): bool
    {
        return DB::transaction(function () use ($id, $status, $reason, $adminId) {
            if (!in_array($status, $this->validStatuses)) {
                throw new \Exception("Status '{$status}' tidak valid");
            }

            $report = Report::findOrFail($id);
            $oldStatus = $report->status;

            $report->status = $status;
            if ($status === 'resolved') {
                $report->resolved_at = now();
            }
            $report->save();

            ReportStatusHistory::create([
                'report_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'changed_by_admin_id' => $adminId,
                'changed_by_employee_id' => null,
                'reason' => $reason,
            ]);

            $this->logModeration($report, 'update_status', $oldStatus, $status, $reason, $adminId);

            Cache::tags(['reports', "report_{$id}", "unit_{$report->unit_id}", 'dashboard'])->flush();

            try {
                $student = $report->student;
                $admin = Admin::find($adminId);

                if ($student && $student->email) {
                    Mail::to($student->email)->send(new ReportStatusChangedMail(
                        $student->name,
                        $report->tracking_code,
                        $report->title,
                        $oldStatus,
                        $status,
                        $reason,
                        $admin?->nama ?? 'Admin',
                        'Admin'
                    ));
                }
            } catch (\Exception $e) {
                Log::warning("Failed to send status change notification: " . $e->getMessage());
            }

            return true;
        });
    }

    public function reply(int $id, string $reply, bool $isPublic, int $adminId): ReportReply
    {
        return DB::transaction(function () use ($id, $reply, $isPublic, $adminId) {
            $report = Report::findOrFail($id);

            $reportReply = ReportReply::create([
                'report_id' => $id,
                'admin_id' => $adminId,
                'employee_id' => null,
                'reply' => $reply,
                'is_public' => $isPublic,
            ]);


            if (in_array($report->status, ['new', 'in_progress'])) {
                $this->updateStatus($id, 'replied', 'Auto-updated status after admin reply', $adminId);
            }

            Cache::tags(['reports', "report_{$id}", "unit_{$report->unit_id}"])->flush();

            return $reportReply;
        });
    }

    public function bulkUpdateStatus(array $ids, string $status, ?string $reason, int $adminId): int
    {
        return DB::transaction(function () use ($ids, $status, $reason, $adminId) {
            $count = 0;
            foreach ($ids as $id) {
                try {
                    $this->updateStatus($id, $status, $reason, $adminId);
                    $count++;
                } catch (\Exception $e) {
                    continue;
                }
            }
            return $count;
        });
    }

    public function export(array $filters = [])
    {
        $query = Report::with(['student', 'unit', 'category']);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        $reports = $query->get();

        $rows = $reports->map(function ($report) {
            return [
                'ID' => $report->id,
                'Tracking Code' => $report->tracking_code,
                'Unit' => $report->unit?->name ?? '-',
                'Student' => $report->student?->name ?? '-',
                'Category' => $report->category?->name ?? '-',
                'Title' => $report->title,
                'Priority' => $report->priority,
                'Status' => $report->status,
                'Created At' => $report->created_at?->format('Y-m-d H:i:s')
    ];
        })->toArray();

        $headers = array_keys($rows[0] ?? []);
        $filename = 'reports_' . now()->format('Y-m-d_His');

        $exportManager = app(\App\Services\Export\ExportManager::class);
        return $exportManager->getExcelService()->export($rows, $headers, 'Report Export', $filename);
    }

    public function getStats(): array
    {
        return Cache::tags(['reports', 'dashboard'])->remember('admin_report_stats', 300, function () {
            return [
                'total' => Report::count(),
                'new' => Report::where('status', 'new')->count(),
                'in_progress' => Report::where('status', 'in_progress')->count(),
                'resolved' => Report::where('status', 'resolved')->count(),
                'rejected' => Report::where('status', 'rejected')->count(),
                'by_priority' => Report::selectRaw('priority, COUNT(*) as count')->groupBy('priority')->pluck('count', 'priority')->toArray()
    ];
        });
    }

    protected function logModeration(Report $report, string $action, string $oldValue, string $newValue, ?string $reason, int $adminId): void
    {
        ModerationLog::create([
            'admin_id' => $adminId,
            'target_type' => Report::class,
            'target_id' => $report->id,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
