<?php

namespace App\Services\Admin;

use App\Models\UnitType;
use Illuminate\Pagination\LengthAwarePaginator;

class UnitTypeService
{
    public function getUnitTypes(array $filters = []): LengthAwarePaginator
    {
        $query = UnitType::withCount('units');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query->ordered()
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function createUnitType(array $data): UnitType
    {
        return UnitType::create($data);
    }

    public function getUnitTypeDetail(string $id): array
    {
        $unitType = UnitType::withCount('units')
            ->with(['units' => function ($query) {
                $query->latest()->limit(10);
            }])
            ->findOrFail($id);

        return [
            'unit_type' => $unitType,
            'stats' => [
                'total_units' => $unitType->units_count,
                'active_units' => $unitType->units()->where('status_aktif', true)->count(),
                'inactive_units' => $unitType->units()->where('status_aktif', false)->count()
            ]
        ];
    }

    public function updateUnitType(string $id, array $data): UnitType
    {
        $unitType = UnitType::findOrFail($id);
        $unitType->update($data);
        return $unitType;
    }

    public function deleteUnitType(string $id): bool
    {
        $unitType = UnitType::findOrFail($id);
        
        if ($unitType->units()->exists()) {
            throw new \Exception('Tidak dapat menghapus tipe unit karena masih memiliki unit terkait');
        }
        
        return $unitType->delete();
    }

    public function toggleUnitTypeStatus(string $id): UnitType
    {
        $unitType = UnitType::findOrFail($id);
        $unitType->update(['is_active' => !$unitType->is_active]);
        return $unitType;
    }

    public function reorderUnitTypes(array $ids): void
    {
        foreach ($ids as $index => $id) {
            UnitType::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    public function getActiveUnitTypes()
    {
        return UnitType::active()->ordered()->get();
    }
}