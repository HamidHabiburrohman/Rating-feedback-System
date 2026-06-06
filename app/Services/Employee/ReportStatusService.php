<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Reports\Report;
use App\Models\Reports\ReportStatusHistory;

class ReportStatusService
{
    protected Employee $employee;

    protected array $validStatuses = ['new', 'assigned', 'in_progress', 'replied', 'resolved', 'rejected', 'pending_preview'];

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function updateStatus(int $reportId, string $newStatus, ?string $reason = null): bool
    {
        $report = Report::where('id', $reportId)
            ->whereHas('unit', function ($query) {
                $query->whereHas('employeeAssignments', function ($q) {
                    $q->where('employee_id', $this->employee->id)
                        ->where('is_active', true);
                });
            })
            ->first();

        if (!$report) {
            return false;
        }

        if (!$this->isValidTransition($report->status, $newStatus)) {
            return false;
        }

        $oldStatus = $report->status;
        $report->status = $newStatus;

        if ($newStatus === 'resolved') {
            $report->resolved_at = now();
        }

        $report->save();

        $this->logHistory($reportId, $oldStatus, $newStatus, $reason);

        return true;
    }

    public function resolve(int $reportId, ?string $resolutionNote = null): bool
    {
        return $this->updateStatus($reportId, 'resolved', $resolutionNote);
    }

    public function reopen(int $reportId, ?string $reason = null): bool
    {
        $report = Report::find($reportId);
        if (!$report) {
            return false;
        }

        if ($report->status !== 'resolved' && $report->status !== 'rejected') {
            return false;
        }

        return $this->updateStatus($reportId, 'in_progress', $reason);
    }

    public function getHistory(int $reportId): array
    {
        return ReportStatusHistory::where('report_id', $reportId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    protected function isValidTransition(string $oldStatus, string $newStatus): bool
    {
        $allowed = [
            'new' => ['assigned', 'in_progress', 'rejected'],
            'assigned' => ['in_progress', 'replied', 'rejected'],
            'in_progress' => ['replied', 'resolved', 'rejected'],
            'replied' => ['in_progress', 'resolved', 'rejected'],
            'resolved' => ['reopened', 'in_progress'],
            'rejected' => ['reopened', 'in_progress'],
            'pending_preview' => ['new', 'rejected'],
        ];

        if (!isset($allowed[$oldStatus])) {
            return false;
        }

        return in_array($newStatus, $allowed[$oldStatus]);
    }

    protected function logHistory(int $reportId, string $oldStatus, string $newStatus, ?string $reason = null): void
    {
        ReportStatusHistory::create([
            'report_id' => $reportId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by_employee_id' => $this->employee->id,
            'reason' => $reason,
        ]);
    }
}