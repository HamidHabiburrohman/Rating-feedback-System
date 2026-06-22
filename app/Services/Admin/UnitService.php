<?php

namespace App\Services\Admin;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitType;
use App\Models\Unit\UnitDepartment;
use App\Models\Unit\Facility;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnitService extends BaseAdminService
{
    public function getFilteredUnits(array $filters = [])
    {
        $query = Unit::with(['unitType', 'unitDepartment', 'primaryPhoto']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['type'])) {
            $typeIds = is_array($filters['type']) ? $filters['type'] : explode(',', $filters['type']);
            $query->whereIn('unit_type_id', $typeIds);
        }

        if (!empty($filters['department'])) {
            $query->where('unit_department_id', $filters['department']);
        }

        if (!empty($filters['status'])) {
            $statuses = is_array($filters['status']) ? $filters['status'] : explode(',', $filters['status']);
            $query->whereIn('operational_status', $statuses);
        }

        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderByDesc('avg_rating')->orderByDesc('total_ratings');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getFormData(): array
    {
        return Cache::tags(['units', 'dropdown'])->remember('unit_form_data', 3600, function () {
            return [
                'unit_types' => UnitType::where('is_active', true)->orderBy('name')->get(['id', 'name']),
                'departments' => UnitDepartment::where('is_active', true)->orderBy('name')->get(['id', 'name']),
                'facilities' => Facility::where('is_active', true)->orderBy('name')->get(['id', 'name', 'icon']),
            ];
        });
    }

    public function getUnitTypesForFilter()
    {
        return UnitType::where('is_active', true)->orderBy('name')->get();
    }

    public function create(array $data): Unit
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name'] . '-' . uniqid());
            $data['is_active'] = $data['is_active'] ?? true;
            $facilities = $data['facilities'] ?? [];
            unset($data['facilities']);

            $unit = Unit::create($data);

            if (!empty($facilities)) {
                $unit->facilities()->attach($facilities);
            }

            Cache::tags(['units', 'landing', 'dashboard'])->flush();
            return $unit->fresh(['unitType', 'unitDepartment', 'facilities']);
        });
    }

    public function getDetail(int $id): array
    {
        $cacheKey = "admin_unit_detail_{$id}";
        return Cache::tags(['units', "unit_{$id}"])->remember($cacheKey, 300, function () use ($id) {
            $unit = Unit::with([
                'unitType',
                'unitDepartment',
                'facilities',
                'primaryPhoto',
                'photos' => fn($q) => $q->orderBy('sort_order'),
                'qrCodes',
                'employeeAssignments.employee'
            ])->findOrFail($id);

            return [
                'unit' => $unit,
                'stats' => [
                    'total_ratings' => $unit->ratings()->count(),
                    'avg_rating' => round($unit->ratings()->avg('overall_score') ?? 0, 2),
                    'total_reports' => $unit->reports()->count(),
                    'pending_reports' => $unit->reports()->whereIn('status', ['new', 'in_progress'])->count(),
                    'total_employees' => $unit->employeeAssignments()->where('is_active', true)->count(),
                    'total_visits' => $unit->unitVisits()->count(),
                ],
                'recent_ratings' => $unit->ratings()->with('student')->latest()->limit(5)->get(),
                'recent_reports' => $unit->reports()->with('student')->latest()->limit(5)->get(),
            ];
        });
    }

    public function getEditData(int $id): array
    {
        $unit = Unit::with(['unitType', 'unitDepartment', 'facilities'])->findOrFail($id);
        $formData = $this->getFormData();

        return [
            'unit' => $unit,
            'unit_types' => $formData['unit_types'],
            'departments' => $formData['departments'],
            'facilities' => $formData['facilities'],
            'selected_facilities' => $unit->facilities->pluck('id')->toArray(),
        ];
    }

    public function update(int $id, array $data): Unit
    {
        return DB::transaction(function () use ($id, $data) {
            $unit = Unit::findOrFail($id);

            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name'] . '-' . $id);
            }

            $facilities = $data['facilities'] ?? null;
            unset($data['facilities']);

            $unit->update($data);

            if ($facilities !== null) {
                $unit->facilities()->sync($facilities);
            }

            Cache::tags(['units', "unit_{$id}", 'landing', 'dashboard'])->flush();
            return $unit->fresh(['unitType', 'unitDepartment', 'facilities']);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            Cache::tags(['units', "unit_{$id}", 'landing', 'dashboard'])->flush();
            return true;
        });
    }

    public function getTrashed(array $filters = [])
    {
        $query = Unit::onlyTrashed()->with(['unitType', 'unitDepartment']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->latest('deleted_at')->paginate($filters['per_page'] ?? 10);
    }

    public function restore(int $id): Unit
    {
        return DB::transaction(function () use ($id) {
            $unit = Unit::onlyTrashed()->findOrFail($id);
            $unit->restore();
            Cache::tags(['units', "unit_{$id}", 'landing', 'dashboard'])->flush();
            return $unit;
        });
    }

    public function forceDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $unit = Unit::onlyTrashed()->findOrFail($id);
            $unit->forceDelete();
            Cache::tags(['units', "unit_{$id}", 'landing', 'dashboard'])->flush();
            return true;
        });
    }

    public function toggleStatus(int $id): Unit
    {
        return DB::transaction(function () use ($id) {
            $unit = Unit::findOrFail($id);
            $unit->update(['is_active' => !$unit->is_active]);
            Cache::tags(['units', "unit_{$id}", 'landing', 'dashboard'])->flush();
            return $unit->fresh();
        });
    }

    public function syncFacilities(int $id, array $facilityIds): Unit
    {
        return DB::transaction(function () use ($id, $facilityIds) {
            $unit = Unit::findOrFail($id);
            $unit->facilities()->sync($facilityIds);
            Cache::tags(['units', "unit_{$id}"])->flush();
            return $unit->fresh(['facilities']);
        });
    }

    public function bulkDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = Unit::whereIn('id', $ids)->delete();
            Cache::tags(['units', 'landing', 'dashboard'])->flush();
            return $count;
        });
    }

    public function bulkRestore(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = Unit::onlyTrashed()->whereIn('id', $ids)->restore();
            Cache::tags(['units', 'landing', 'dashboard'])->flush();
            return $count;
        });
    }

    public function bulkForceDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = Unit::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            Cache::tags(['units', 'landing', 'dashboard'])->flush();
            return $count;
        });
    }

    public function bulkActivate(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = Unit::whereIn('id', $ids)->update(['is_active' => true]);
            Cache::tags(['units', 'landing', 'dashboard'])->flush();
            return $count;
        });
    }
}
