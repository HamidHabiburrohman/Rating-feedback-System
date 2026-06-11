<?php

namespace App\Services\Admin;

use App\Models\Unit\UnitType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnitTypeService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = UnitType::withCount('units');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query->orderBy('sort_order')->orderBy('name')->get();
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
            $data['sort_order'] = $data['sort_order'] ?? UnitType::max('sort_order') + 1;

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
        return DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                UnitType::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }

            Cache::tags(['units', 'dropdown', 'landing'])->flush();
            return true;
        });
    }
}