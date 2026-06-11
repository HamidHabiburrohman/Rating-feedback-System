<?php

namespace App\Services\Employee;

use App\Models\Report\Report;
use App\Models\Report\ReportStatusHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportStatusService extends BaseEmployeeService
{
    protected array $validStatuses = ['in_progress', 'replied', 'resolved'];

    public function updateStatus(int $reportId, string $status, ?string $reason, int $employeeId): bool
    {
        return DB::transaction(function () use ($reportId, $status, $reason, $employeeId) {
            if (!in_array($status, $this->validStatuses)) {
                throw new \Exception("Status '{$status}' tidak valid untuk employee.");
            }

            $report = Report::findOrFail($reportId);

            if (!$this->isAssignedToUnit($report->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk mengubah status laporan unit ini.');
            }

            $oldStatus = $report->status;
            if ($oldStatus === $status) {
                return true; // Tidak ada perubahan
            }

            $report->status = $status;
            if ($status === 'resolved') {
                $report->resolved_at = now();
            }
            $report->save();

            ReportStatusHistory::create([
                'report_id' => $reportId,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'changed_by_employee_id' => $employeeId,
                'changed_by_admin_id' => null,
                'reason' => $reason,
            ]);

            Cache::tags(['reports', "report_{$reportId}", "unit_{$report->unit_id}", "employee_{$employeeId}", 'dashboard'])->flush();

            return true;
        });
    }

    public function resolve(int $reportId, ?string $reason, int $employeeId): bool
    {
        return $this->updateStatus($reportId, 'resolved', $reason, $employeeId);
    }

    public function reopen(int $reportId, ?string $reason, int $employeeId): bool
    {
        return DB::transaction(function () use ($reportId, $reason, $employeeId) {
            $report = Report::findOrFail($reportId);

            if (!$this->isAssignedToUnit($report->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk membuka kembali laporan unit ini.');
            }

            if (!in_array($report->status, ['resolved', 'rejected'])) {
                throw new \Exception('Hanya laporan yang sudah diselesaikan atau ditolak yang bisa dibuka kembali.');
            }

            $oldStatus = $report->status;
            $report->status = 'in_progress';
            $report->resolved_at = null;
            $report->save();

            ReportStatusHistory::create([
                'report_id' => $reportId,
                'old_status' => $oldStatus,
                'new_status' => 'in_progress',
                'changed_by_employee_id' => $employeeId,
                'changed_by_admin_id' => null,
                'reason' => $reason ?? 'Laporan dibuka kembali oleh employee',
            ]);

            Cache::tags(['reports', "report_{$reportId}", "unit_{$report->unit_id}", "employee_{$employeeId}", 'dashboard'])->flush();

            return true;
        });
    }

    public function getHistory(int $reportId): array
    {
        $report = Report::findOrFail($reportId);
        
        if (!$this->isAssignedToUnit($report->unit_id)) {
            throw new \Exception('Anda tidak memiliki akses ke laporan ini.');
        }

        return $report->statusHistory()
            ->with(['employee', 'admin'])
            ->latest()
            ->get()
            ->toArray();
    }
}