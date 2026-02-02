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

    public function getAllUnitTypes()
    {
        return UnitType::active()->ordered()->pluck('name');
    }

    public function createUnit(array $data, $fotoFile = null): Unit
    {
        if ($fotoFile) {
            $data['foto_unit'] = $this->storeFotoUnit($fotoFile);
        }

        return Unit::create($data);
    }

    public function getUnitDetail(string $id): array
    {
        $unit = Unit::with('unitType')->findOrFail($id);
        
        return [
            'unit' => $unit,
            'stats' => $this->getUnitStats($unit),
            'rating_by_category' => $this->getCategoryStats($id),
            'recent_visits' => $unit->visits()->latest('waktu_masuk')->limit(10)->get(),
            'recent_ratings' => $unit->ratings()->with('scores.category')->latest()->limit(10)->get()
        ];
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

    public function toggleUnitStatus(string $id): Unit
    {
        $unit = Unit::findOrFail($id);
        $unit->update(['status_aktif' => !$unit->status_aktif]);
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

    private function getUnitStats(Unit $unit): array
    {
        return [
            'average_rating' => round($unit->ratings()->avg('rata_rata') ?? 0, 1),
            'total_ratings' => $unit->ratings()->count(),
            'total_visits' => $unit->visits()->count(),
            'total_employees' => $unit->employees()->count(),
            'total_reports' => $unit->reports()->count()
        ];
    }

    private function getCategoryStats(string $unitId)
    {
        return DB::table('rating_categories')
            ->leftJoin('rating_scores', 'rating_categories.id', '=', 'rating_scores.rating_category_id')
            ->where('rating_categories.unit_id', $unitId)
            ->select(
                'rating_categories.nama_kategori as kategori',
                DB::raw('COALESCE(AVG(rating_scores.skor), 0) as rata_rata')
            )
            ->groupBy('rating_categories.id', 'rating_categories.nama_kategori')
            ->get()
            ->map(fn($item) => [
                'kategori' => $item->kategori,
                'rata_rata' => round($item->rata_rata, 1)
            ]);
    }
}