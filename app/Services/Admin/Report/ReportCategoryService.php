<?php

namespace App\Services\Admin\Report;

use App\Models\Report\ReportCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportCategoryService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = ReportCategory::withCount('reports');

        $query->when(!empty($filters['search']), function ($q) use ($filters) {
            $q->where('name', 'like', "%{$filters['search']}%");
        });

        $query->when(isset($filters['status']) && $filters['status'] !== '', function ($q) use ($filters) {
            $isActive = in_array($filters['status'], ['active', '1', true], true);
            $q->where('is_active', $isActive);
        });

        $sortField = $filters['sort'] ?? 'name';
        $sortOrder = $filters['order'] ?? 'asc';

        $allowedSorts = ['name', 'created_at', 'reports_count'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        $query->orderBy($sortField, $sortOrder);

        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage);
    }

    public function create(array $data): ReportCategory
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? true;
            $category = ReportCategory::create($data);
            Cache::tags(['reports', 'dropdown'])->flush();
            return $category;
        });
    }

    public function update(int $id, array $data): ReportCategory
    {
        return DB::transaction(function () use ($id, $data) {
            $category = ReportCategory::findOrFail($id);
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $category->update($data);
            Cache::tags(['reports', 'dropdown'])->flush();
            return $category->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $category = ReportCategory::findOrFail($id);
            if ($category->reports()->count() > 0) {
                throw new \Exception('Kategori tidak dapat dihapus karena masih digunakan oleh laporan');
            }
            $category->delete();
            Cache::tags(['reports', 'dropdown'])->flush();
            return true;
        });
    }

    public function toggleActive(int $id): ReportCategory
    {
        return DB::transaction(function () use ($id) {
            $category = ReportCategory::findOrFail($id);
            $category->update(['is_active' => !$category->is_active]);
            Cache::tags(['reports', 'dropdown'])->flush();
            return $category->fresh();
        });
    }
}
