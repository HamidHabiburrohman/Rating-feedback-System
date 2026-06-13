<?php

namespace App\Services\Student;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitType;
use App\Models\Unit\UnitDepartment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UnitService extends BaseStudentService
{
    protected Unit $unit;
    protected UnitType $unitType;
    protected UnitDepartment $unitDepartment;

    public function __construct(Unit $unit, UnitType $unitType, UnitDepartment $unitDepartment)
    {
        $this->unit = $unit;
        $this->unitType = $unitType;
        $this->unitDepartment = $unitDepartment;
    }

    public function getFilteredUnits(array $filters = []): LengthAwarePaginator
    {
        $query = $this->unit
            ->with(['unitType', 'unitDepartment', 'primaryPhoto', 'photos'])
            ->where('is_active', true);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%")
                    ->orWhere('location', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('unit_type_id', $filters['type']);
        }

        if (!empty($filters['department'])) {
            $query->where('unit_department_id', $filters['department']);
        }

        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'latest':
                $query->latest();
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderBy('avg_rating', 'desc')->orderBy('total_ratings', 'desc');
                break;
            default:
                $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 6);
    }

    public function getActiveUnitTypes(): array
    {
        return Cache::tags(['units', 'dropdown'])->remember('active_unit_types', 86400, function () {
            return $this->unitType
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'icon', 'slug'])
                ->toArray();
        });
    }

    public function getActiveDepartments(): array
    {
        return Cache::tags(['units', 'dropdown'])->remember('active_departments', 86400, function () {
            return $this->unitDepartment
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug'])
                ->toArray();
        });
    }

    public function getDetailBySlug(string $slug): Unit
    {
        $cacheKey = "unit_detail_{$slug}";

        return Cache::tags(['units', 'landing'])->remember($cacheKey, 300, function () use ($slug) {
            $unit = $this->unit->with([
                'unitType',
                'unitDepartment',
                'facilities',
                'primaryPhoto',
                'photos' => fn($q) => $q->orderBy('sort_order'),
                'ratings' => fn($q) => $q->with('student')->where('status', 'active')->latest()->limit(10)
            ])->where('slug', $slug)->where('is_active', true)->first();

            if (!$unit) {
                throw new ModelNotFoundException("Unit with slug {$slug} not found");
            }

            return $unit;
        });
    }
}
