<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Feedback\Rating;
use App\Models\Reports\Report;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected Employee $employee;

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getStats(): array
    {
        $assignedUnitIds = $this->getAssignedUnitIds();

        return [
            'total_ratings' => Rating::whereIn('unit_id', $assignedUnitIds)->count(),
            'total_reports' => Report::whereIn('unit_id', $assignedUnitIds)->count(),
            'pending_reports' => Report::whereIn('unit_id', $assignedUnitIds)
                ->whereIn('status', ['new', 'assigned', 'in_progress'])
                ->count(),
            'resolved_reports' => Report::whereIn('unit_id', $assignedUnitIds)
                ->where('status', 'resolved')
                ->count(),
            'units_count' => count($assignedUnitIds),
        ];
    }

    public function getRecentRatings(int $limit = 10): array
    {
        $assignedUnitIds = $this->getAssignedUnitIds();

        return Rating::whereIn('unit_id', $assignedUnitIds)
            ->with(['student', 'unit'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getRecentReports(int $limit = 10): array
    {
        $assignedUnitIds = $this->getAssignedUnitIds();

        return Report::whereIn('unit_id', $assignedUnitIds)
            ->with(['student', 'unit', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    protected function getAssignedUnitIds(): array
    {
        return $this->employee->unitAssignments()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
            })
            ->pluck('unit_id')
            ->toArray();
    }
}