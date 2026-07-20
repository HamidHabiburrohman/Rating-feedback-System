<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Feedback\Rating;
use App\Models\Report\Report;
use App\Models\Feedback\RatingReply;
use App\Models\Report\ReportReply;
use Illuminate\Support\Facades\Cache;

class DashboardService extends BaseEmployeeService
{
    public function getStats(int $employeeId): array
    {
        $cacheKey = "employee_dashboard_stats_{$employeeId}";
        
        return Cache::tags(['dashboard', "employee_{$employeeId}"])->remember($cacheKey, 120, function () use ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            
            $assignedUnitIds = $employee->unitAssignments()
                ->where('is_active', true)
                ->pluck('unit_id')
                ->toArray();

            return [
                'total_assigned_units' => count($assignedUnitIds),
                'total_ratings_received' => Rating::whereIn('unit_id', $assignedUnitIds)->count(),
                'total_reports_received' => Report::whereIn('unit_id', $assignedUnitIds)->count(),
                'pending_reports' => Report::whereIn('unit_id', $assignedUnitIds)
                    ->whereIn('status', ['new', 'in_progress'])
                    ->count(),
                'resolved_reports' => Report::whereIn('unit_id', $assignedUnitIds)
                    ->where('status', 'resolved')
                    ->count(),
                'total_rating_replies' => RatingReply::where('employee_id', $employeeId)->count(),
                'total_report_replies' => ReportReply::where('employee_id', $employeeId)->count(),
                'avg_rating' => round(Rating::whereIn('unit_id', $assignedUnitIds)->avg('overall_score') ?? 0, 2)
    ];
        });
    }

    public function getRecentRatings(int $employeeId, int $limit = 5): array
    {
        $cacheKey = "employee_recent_ratings_{$employeeId}";
        
        return Cache::tags(['dashboard', "employee_{$employeeId}", 'ratings'])->remember($cacheKey, 180, function () use ($employeeId, $limit) {
            $employee = Employee::findOrFail($employeeId);
            
            $assignedUnitIds = $employee->unitAssignments()
                ->where('is_active', true)
                ->pluck('unit_id')
                ->toArray();

            return Rating::with(['student', 'unit'])
                ->whereIn('unit_id', $assignedUnitIds)
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getRecentReports(int $employeeId, int $limit = 5): array
    {
        $cacheKey = "employee_recent_reports_{$employeeId}";
        
        return Cache::tags(['dashboard', "employee_{$employeeId}", 'reports'])->remember($cacheKey, 180, function () use ($employeeId, $limit) {
            $employee = Employee::findOrFail($employeeId);
            
            $assignedUnitIds = $employee->unitAssignments()
                ->where('is_active', true)
                ->pluck('unit_id')
                ->toArray();

            return Report::with(['student', 'unit', 'category'])
                ->whereIn('unit_id', $assignedUnitIds)
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getAssignedUnits(int $employeeId): array
    {
        $cacheKey = "employee_assigned_units_{$employeeId}";
        
        return Cache::tags(['dashboard', "employee_{$employeeId}", 'units'])->remember($cacheKey, 300, function () use ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            
            return $employee->unitAssignments()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                })
                ->with(['unit.unitType', 'unit.primaryPhoto'])
                ->get()
                ->map(function ($assignment) {
                    $unit = $assignment->unit;
                    return [
                        'assignment_id' => $assignment->id,
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_slug' => $unit->slug,
                        'role_in_unit' => $assignment->role_in_unit,
                        'started_at' => $assignment->started_at,
                        'total_ratings' => $unit->total_ratings,
                        'avg_rating' => $unit->avg_rating,
                        'primary_photo' => $unit->primaryPhoto
    ];
                })
                ->toArray();
        });
    }
}