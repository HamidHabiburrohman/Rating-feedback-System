<?php

namespace App\Services\Admin;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\RatingCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class UnitService
{
    public function getUnits(array $filters = []): LengthAwarePaginator
    {
        $query = Unit::with('unitType');

        if (isset($filters['type'])) {
            $typeFilters = explode(',', $filters['type']);
            $typeIds = UnitType::whereIn('name', $typeFilters)
                ->pluck('id')
                ->toArray();
            
            if (!empty($typeIds)) {
                $query->whereIn('type_id', $typeIds);
            }
        }

        if (isset($filters['status'])) {
            $statusFilters = explode(',', $filters['status']);
            $statusConditions = [];
            
            if (in_array('aktif', $statusFilters)) {
                $statusConditions[] = true;
            }
            if (in_array('nonaktif', $statusFilters)) {
                $statusConditions[] = false;
            }
            
            if (!empty($statusConditions)) {
                $query->whereIn('status_aktif', $statusConditions);
            }
        }

        if (isset($filters['gedung'])) {
            $query->where('gedung', $filters['gedung']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_unit', 'like', "%$search%")
                  ->orWhere('kode_unit', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%");
            });
        }

        return $query->orderBy($filters['sort'] ?? 'nama_unit', $filters['order'] ?? 'asc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function createUnit(array $data): Unit
    {
        return Unit::create($data);
    }

    public function getUnitDetail(string $id): array
    {
        $unit = Unit::with('unitType')
            ->withCount(['ratings', 'visits', 'employees', 'reports'])
            ->with(['employees' => fn($q) => $q->orderBy('status')->orderBy('nama')])
            ->findOrFail($id);

        return [
            'unit' => $unit,
            'stats' => [
                'average_rating' => round($unit->ratings()->avg('rata_rata') ?? 0, 1),
                'total_ratings' => $unit->ratings_count,
                'total_visits' => $unit->visits_count,
                'total_employees' => $unit->employees_count,
                'total_reports' => $unit->reports_count
            ],
            'rating_by_category' => $this->getCategoryStats($id),
            'recent_visits' => $unit->visits()->latest('waktu_masuk')->limit(10)->get(),
            'recent_ratings' => $unit->ratings()->with('scores.category')->latest()->limit(10)->get()
        ];
    }

    public function updateUnit(string $id, array $data): Unit
    {
        $unit = Unit::findOrFail($id);
        $unit->update($data);
        return $unit;
    }

    public function deleteUnit(string $id): void
    {
        Unit::findOrFail($id)->delete();
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