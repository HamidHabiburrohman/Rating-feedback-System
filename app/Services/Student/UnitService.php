<?php

namespace App\Services\Student;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitDepartment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

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
        try {
            $query = $this->unit
                ->with(['type', 'department', 'primaryPhoto', 'photos'])
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
                case 'rating':
                    $query->orderByDesc('avg_rating');
                    break;
                case 'popular':
                    $query->orderByDesc('total_ratings');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }

            $perPage = $filters['per_page'] ?? 6;

            return $query->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Failed to get filtered units', [
                'filters' => $filters,
                'error' => $e->getMessage()
            ]);

            return new LengthAwarePaginator([], 0, 12, 1);
        }
    }

    public function getActiveUnitTypes(): array
    {
        try {
            return $this->unitType
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get active unit types', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getActiveDepartments(): array
    {
        try {
            return $this->unitDepartment
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get active departments', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getDetailBySlug(string $slug): Unit
    {
        try {
            $unit = $this->unit->with([
                'type',
                'department',
                'facilities',
                'primaryPhoto',
                'photos' => function ($q) {
                    $q->orderBy('sort_order');
                },
                'ratings' => function ($q) {
                    $q->with('student')
                        ->where('status', 'active')
                        ->latest()
                        ->limit(10);
                }
            ])
                ->where('slug', $slug)
                ->where('is_active', true)
                ->first();

            if (!$unit) {
                throw new ModelNotFoundException();
            }

            return $unit;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to get unit detail', [
                'slug' => $slug,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Gagal mengambil detail unit');
        }
    }
}