<?php

namespace App\Services\Admin\Unit;

use App\Models\Unit\UnitType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\Shared\BaseAdminService;
use Illuminate\Support\Str;

class UnitTypeService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = UnitType::withCount('units');

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

        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage);
    }

    public function findById(int $id): UnitType
    {
        return UnitType::withCount('units')->findOrFail($id);
    }

    public function create(array $data): UnitType
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? true;
            $unitType = UnitType::create($data);
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return $unitType;
        });
    }

    public function update(int $id, array $data): UnitType
    {
        return DB::transaction(function () use ($id, $data) {
            $unitType = UnitType::findOrFail($id);
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $unitType->update($data);
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return $unitType->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $unitType = UnitType::findOrFail($id);
            if ($unitType->units()->count() > 0) {
                throw new \Exception('Tipe unit tidak dapat dihapus karena masih digunakan oleh unit lain');
            }
            $unitType->delete();
            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return true;
        });
    }

    public function reorder(array $orders): bool
    {
        return true;
    }
}
