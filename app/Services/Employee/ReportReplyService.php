<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Reports\Report;
use App\Models\Reports\ReportReply;

class ReportReplyService
{
    protected Employee $employee;

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getRepliesForReport(int $reportId): array
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
            return [];
        }

        return $report->replies()->with('employee')->get()->toArray();
    }

    public function create(int $reportId, string $reply, bool $isPublic = true): ?ReportReply
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
            return null;
        }

        $replyModel = ReportReply::create([
            'report_id' => $reportId,
            'employee_id' => $this->employee->id,
            'reply' => $reply,
            'is_public' => $isPublic,
        ]);

        $report->update(['replied_at' => now()]);

        return $replyModel;
    }

    public function update(int $replyId, string $reply): ?ReportReply
    {
        $replyModel = ReportReply::where('id', $replyId)
            ->where('employee_id', $this->employee->id)
            ->first();

        if (!$replyModel) {
            return null;
        }

        $replyModel->reply = $reply;
        $replyModel->save();

        return $replyModel;
    }

    public function delete(int $replyId): bool
    {
        $replyModel = ReportReply::where('id', $replyId)
            ->where('employee_id', $this->employee->id)
            ->first();

        if (!$replyModel) {
            return false;
        }

        return $replyModel->delete();
    }
}