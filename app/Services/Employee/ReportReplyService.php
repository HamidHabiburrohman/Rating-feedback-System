<?php

namespace App\Services\Employee;

use App\Models\Report\Report;
use App\Models\Report\ReportReply;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportReplyService extends BaseEmployeeService
{
    protected ReportStatusService $statusService;

    public function __construct(ReportStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function reply(int $reportId, string $reply, int $employeeId): ReportReply
    {
        return DB::transaction(function () use ($reportId, $reply, $employeeId) {
            $report = Report::findOrFail($reportId);

            if (!$this->isAssignedToUnit($report->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk membalas laporan unit ini.');
            }

            $reportReply = ReportReply::create([
                'report_id' => $reportId,
                'employee_id' => $employeeId,
                'admin_id' => null,
                'reply' => $reply,
                'is_public' => true,
            ]);

            $report->update(['last_replied_at' => now()]);

            // Auto-update status ke 'replied' jika masih 'new' atau 'in_progress'
            if (in_array($report->status, ['new', 'in_progress'])) {
                $this->statusService->updateStatus(
                    $reportId, 
                    'replied', 
                    'Auto-updated status after employee reply', 
                    $employeeId
                );
            }

            Cache::tags(['reports', "report_{$reportId}", "unit_{$report->unit_id}", 'dashboard'])->flush();

            return $reportReply->fresh(['employee']);
        });
    }

    public function updateReply(int $replyId, string $reply, int $employeeId): ReportReply
    {
        return DB::transaction(function () use ($replyId, $reply, $employeeId) {
            $reportReply = ReportReply::where('id', $replyId)
                ->where('employee_id', $employeeId)
                ->firstOrFail();

            $report = $reportReply->report;
            if (!$this->isAssignedToUnit($report->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk mengubah balasan ini.');
            }

            $reportReply->update(['reply' => $reply]);

            Cache::tags(['reports', "report_{$report->id}", "unit_{$report->unit_id}"])->flush();

            return $reportReply;
        });
    }

    public function deleteReply(int $replyId, int $employeeId): bool
    {
        return DB::transaction(function () use ($replyId, $employeeId) {
            $reportReply = ReportReply::where('id', $replyId)
                ->where('employee_id', $employeeId)
                ->firstOrFail();

            $report = $reportReply->report;
            if (!$this->isAssignedToUnit($report->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk menghapus balasan ini.');
            }

            $reportReply->delete();

            Cache::tags(['reports', "report_{$report->id}", "unit_{$report->unit_id}"])->flush();

            return true;
        });
    }
}