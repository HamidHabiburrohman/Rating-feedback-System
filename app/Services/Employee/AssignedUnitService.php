<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Units\Unit;
use Illuminate\Database\Eloquent\Collection;

class AssignedUnitService
{
    protected Employee $employee;

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getAll(): Collection
    {
        return $this->employee->assignedUnits()
            ->wherePivot('is_active', true)
            ->wherePivot(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
            })
            ->get();
    }

    public function findById(int $unitId): ?Unit
    {
        $unit = Unit::whereHas('employeeAssignments', function ($query) {
            $query->where('employee_id', $this->employee->id)
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                });
        })->where('units.id', $unitId)->first();

        return $unit;
    }

    public function getDetails(int $unitId): ?array
    {
        $unit = $this->findById($unitId);
        if (!$unit) {
            return null;
        }

        $unit->load(['unitType', 'unitDepartment', 'facilities', 'photos', 'primaryQrCode']);

        $stats = [
            'total_ratings' => $unit->ratings()->count(),
            'average_rating' => $unit->ratings()->avg('overall_score'),
            'total_reports' => $unit->reports()->count(),
            'pending_reports' => $unit->reports()->whereIn('status', ['new', 'assigned', 'in_progress'])->count(),
        ];

        return [
            'unit' => $unit,
            'stats' => $stats,
        ];
    }

    public function getRatingCategories(): Collection
    {
        return \App\Models\Feedback\RatingCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}