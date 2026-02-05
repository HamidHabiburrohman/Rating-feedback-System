<?php

namespace App\Services\Admin;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\RatingCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class UnitService
{
    public function getUnits(array $filters = []): LengthAwarePaginator
    {
        $query = Unit::with('unitType');

        if (isset($filters['search'])) {
            $this->applySearchFilter($query, $filters['search']);
        }

        if (isset($filters['status'])) {
            $this->applyStatusFilter($query, $filters['status']);
        }

        if (isset($filters['type'])) {
            $this->applyTypeFilter($query, $filters['type']);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 10;

        return $query->orderBy($sort, $order)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getUnitTypeNamesForFilter()
    {
        return UnitType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name');
    }

    public function getAllUnitTypesForForm()
    {
        return UnitType::select('id', 'name', 'is_active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function createUnit(array $data, $fotoFile = null): Unit
    {
        if ($fotoFile) {
            $data['foto_unit'] = $this->storeFotoUnit($fotoFile);
        }

        return Unit::create($data);
    }

    public function findUnit(string $id): Unit
    {
        return Unit::with('unitType')->findOrFail($id);
    }

    public function updateUnit(string $id, array $data, $fotoFile = null, bool $removeFoto = false): Unit
    {
        $unit = Unit::findOrFail($id);

        if ($removeFoto && $unit->foto_unit) {
            $this->deleteFotoUnit($unit->foto_unit);
            $data['foto_unit'] = null;
        }

        if ($fotoFile) {
            if ($unit->foto_unit) {
                $this->deleteFotoUnit($unit->foto_unit);
            }
            $data['foto_unit'] = $this->storeFotoUnit($fotoFile);
        }

        $unit->update($data);
        return $unit;
    }

    public function deleteUnit(string $id): void
    {
        $unit = Unit::findOrFail($id);

        if ($unit->foto_unit) {
            $this->deleteFotoUnit($unit->foto_unit);
        }

        $unit->delete();
    }

    public function updateUnitStatus(string $id, string $status): Unit
    {
        $unit = Unit::findOrFail($id);
        $unit->update(['status' => $status]);
        return $unit;
    }

    public function getUnitCategories(string $unitId)
    {
        return RatingCategory::where('unit_id', $unitId)
            ->orderBy('urutan')
            ->get();
    }

    private function applySearchFilter($query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('nama_unit', 'LIKE', "%{$search}%")
                ->orWhere('kode_unit', 'LIKE', "%{$search}%")
                ->orWhere('lokasi', 'LIKE', "%{$search}%");
        });
    }

    private function applyStatusFilter($query, string $status): void
    {
        $statusFilters = explode(',', $status);
        $query->whereIn('status', $statusFilters);
    }

    private function applyTypeFilter($query, string $type): void
    {
        $typeFilters = explode(',', $type);
        $typeIds = UnitType::whereIn('name', $typeFilters)
            ->pluck('id')
            ->toArray();

        if (!empty($typeIds)) {
            $query->whereIn('type_id', $typeIds);
        }
    }

    private function storeFotoUnit($fotoFile): string
    {
        return $fotoFile->store('units', 'public');
    }

    private function deleteFotoUnit(string $fotoPath): void
    {
        Storage::disk('public')->delete($fotoPath);
    }
}