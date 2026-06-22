<?php

namespace App\Services\Admin;

use App\Models\Unit\UnitDepartment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnitDepartmentService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = UnitDepartment::withCount('units');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status'] === 'active');
        }

        $sortField = $filters['sort'] ?? 'name';
        $sortOrder = $filters['order'] ?? 'asc';

        $allowedSorts = ['name', 'created_at', 'units_count'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';

        $query->orderBy($sortField, $sortOrder);

        return $query->paginate(10);
    }

    public function findById(int $id): UnitDepartment
    {
        return UnitDepartment::withCount('units')->findOrFail($id);
    }

    public function create(array $data): UnitDepartment
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? true;
            $department = UnitDepartment::create($data);
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return $department;
        });
    }

    public function update(int $id, array $data): UnitDepartment
    {
        return DB::transaction(function () use ($id, $data) {
            $department = UnitDepartment::findOrFail($id);
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $department->update($data);
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return $department->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $department = UnitDepartment::findOrFail($id);
            if ($department->units()->count() > 0) {
                throw new \Exception('Departemen tidak dapat dihapus karena masih digunakan oleh unit lain');
            }
            $department->delete();
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return true;
        });
    }

    public function toggleStatus(int $id): UnitDepartment
    {
        return DB::transaction(function () use ($id) {
            $department = UnitDepartment::findOrFail($id);
            $department->update(['is_active' => !$department->is_active]);
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return $department->fresh();
        });
    }

    public function getStats(): array
    {
        return Cache::tags(['units', 'dropdown'])->remember('department_stats', 300, function () {
            return [
                'total' => UnitDepartment::count(),
                'active' => UnitDepartment::where('is_active', true)->count(),
                'inactive' => UnitDepartment::where('is_active', false)->count(),
                'with_units' => UnitDepartment::has('units')->count(),
                'top_departments' => UnitDepartment::withCount('units')
                    ->orderByDesc('units_count')
                    ->limit(5)
                    ->get(['id', 'name', 'units_count'])
                    ->toArray(),
            ];
        });
    }
}
