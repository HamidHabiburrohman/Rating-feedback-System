<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Feedback\Rating;
use App\Models\Report\Report;
use Illuminate\Support\Facades\Cache;

class AssignedUnitService extends BaseEmployeeService
{
    public function getAssignedUnits(int $employeeId, array $filters = [])
    {
        $employee = Employee::findOrFail($employeeId);
        
        $query = $employee->unitAssignments()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
            })
            ->with(['unit.unitType', 'unit.unitDepartment', 'unit.primaryPhoto', 'unit.photos']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('unit', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getUnitDetail(int $employeeId, int $unitId): array
    {
        if (!$this->isAssignedToUnit($unitId)) {
            throw new \Exception('Anda tidak memiliki akses ke unit ini');
        }

        $cacheKey = "employee_unit_detail_{$employeeId}_{$unitId}";
        
        return Cache::tags(['units', "employee_{$employeeId}", "unit_{$unitId}"])->remember($cacheKey, 300, function () use ($unitId) {
            $unit = Unit::with([
                'unitType',
                'unitDepartment',
                'facilities',
                'primaryPhoto',
                'photos' => fn($q) => $q->orderBy('sort_order'),
                'employeeAssignments.employee'
            ])->findOrFail($unitId);

            return [
                'unit' => $unit,
                'stats' => [
                    'total_ratings' => $unit->ratings()->count(),
                    'avg_rating' => round($unit->ratings()->avg('overall_score') ?? 0, 2),
                    'total_reports' => $unit->reports()->count(),
                    'pending_reports' => $unit->reports()->whereIn('status', ['new', 'in_progress'])->count(),
                    'resolved_reports' => $unit->reports()->where('status', 'resolved')->count(),
                ],
                'recent_ratings' => $unit->ratings()
                    ->with('student')
                    ->latest()
                    ->limit(10)
                    ->get(),
                'recent_reports' => $unit->reports()
                    ->with(['student', 'category'])
                    ->latest()
                    ->limit(10)
                    ->get()
    ];
        });
    }

    public function getUnitRatings(int $employeeId, int $unitId, array $filters = [])
    {
        if (!$this->isAssignedToUnit($unitId)) {
            throw new \Exception('Anda tidak memiliki akses ke unit ini');
        }

        $query = Rating::with(['student', 'scores.category', 'replies.employee'])
            ->where('unit_id', $unitId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'highest':
                $query->orderByDesc('overall_score');
                break;
            case 'lowest':
                $query->orderBy('overall_score');
                break;
            default:
                $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getUnitReports(int $employeeId, int $unitId, array $filters = [])
    {
        if (!$this->isAssignedToUnit($unitId)) {
            throw new \Exception('Anda tidak memiliki akses ke unit ini');
        }

        $query = Report::with(['student', 'category', 'rating', 'replies.employee', 'statusHistory.employee'])
            ->where('unit_id', $unitId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

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

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getRatingCategories(int $unitId): array
    {
        if (!$this->isAssignedToUnit($unitId)) {
            throw new \Exception('Anda tidak memiliki akses ke unit ini');
        }

        $cacheKey = "employee_unit_rating_categories_{$unitId}";
        
        return Cache::tags(['units', "unit_{$unitId}", 'ratings'])->remember($cacheKey, 3600, function () use ($unitId) {
            $unit = Unit::findOrFail($unitId);
            
            $ratings = Rating::where('unit_id', $unitId)
                ->whereIn('status', ['active', 'edited'])
                ->with('scores.category')
                ->get();

            $categoryStats = [];
            
            foreach ($ratings as $rating) {
                foreach ($rating->scores as $score) {
                    $catId = $score->rating_category_id;
                    if (!isset($categoryStats[$catId])) {
                        $categoryStats[$catId] = [
                            'category' => $score->category,
                            'total_scores' => 0,
                            'sum_scores' => 0
    ];
                    }
                    $categoryStats[$catId]['total_scores']++;
                    $categoryStats[$catId]['sum_scores'] += $score->score;
                }
            }

            return array_map(function ($stat) {
                return [
                    'id' => $stat['category']->id,
                    'name' => $stat['category']->name,
                    'slug' => $stat['category']->slug,
                    'avg_score' => round($stat['sum_scores'] / $stat['total_scores'], 2),
                    'total_scores' => $stat['total_scores']
    ];
            }, $categoryStats);
        });
    }
}