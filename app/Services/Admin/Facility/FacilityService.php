<?php

namespace App\Services\Admin\Facility;

use App\Models\Unit\Facility;
use App\Services\Admin\Shared\BaseAdminService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacilityService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = Facility::withCount('units');

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

    public function findById(int $id): Facility
    {
        return Facility::withCount('units')->findOrFail($id);
    }

    public function create(array $data): Facility
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? true;
            $facility = Facility::create($data);
            Cache::tags(['facilities', 'dropdown', 'landing'])->flush();
            return $facility;
        });
    }

    public function update(int $id, array $data): Facility
    {
        return DB::transaction(function () use ($id, $data) {
            $facility = Facility::findOrFail($id);
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $facility->update($data);
            Cache::tags(['facilities', 'dropdown', 'landing'])->flush();
            return $facility->fresh();
        });
    }

    public function getAvailableIcons(): array
    {
        $iconService = app(IconService::class);
        $icons = [];
        foreach ($iconService->getAllIcons() as $key => $icon) {
            $icons[$key] = $icon['name'];
        }
        return $icons;
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $facility = Facility::findOrFail($id);
            if ($facility->units()->count() > 0) {
                throw new \Exception('Fasilitas tidak dapat dihapus karena masih digunakan oleh unit lain');
            }
            $facility->delete();
            Cache::tags(['facilities', 'dropdown', 'landing'])->flush();
            return true;
        });
    }

    public function getPopular(int $limit = 10): array
    {
        return Cache::tags(['facilities', 'dropdown'])->remember("popular_facilities_{$limit}", 3600, function () use ($limit) {
            return Facility::where('is_active', true)
                ->withCount('units')
                ->orderByDesc('units_count')
                ->limit($limit)
                ->get(['id', 'name', 'icon_key', 'units_count'])
                ->toArray();
        });
    }
}
