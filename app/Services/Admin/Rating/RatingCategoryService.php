<?php

namespace App\Services\Admin\Rating;

use App\Models\Feedback\RatingCategory;
use App\Services\Admin\Shared\BaseAdminService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RatingCategoryService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = RatingCategory::withCount('ratingScores');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status']) && in_array($filters['status'], ['active', 'inactive'], true)) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        $sortField = $filters['sort'] ?? 'sort_order';
        $sortOrder = $filters['order'] ?? 'asc';

        $allowedSorts = ['name', 'created_at', 'sort_order', 'rating_scores_count'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'sort_order';
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';

        $query->orderBy($sortField, $sortOrder);

        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage);
    }

    public function getActive(): array
    {
        return Cache::tags(['ratings', 'dropdown'])->remember('active_rating_categories', 86400, function () {
            return RatingCategory::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug'])
                ->toArray();
        });
    }

    public function create(array $data): RatingCategory
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? true;
            $data['sort_order'] = $data['sort_order'] ?? RatingCategory::max('sort_order') + 1;

            $category = RatingCategory::create($data);

            Cache::tags(['ratings', 'dropdown'])->flush();

            return $category;
        });
    }

    public function update(int $id, array $data): RatingCategory
    {
        return DB::transaction(function () use ($id, $data) {
            $category = RatingCategory::findOrFail($id);

            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $category->update($data);

            Cache::tags(['ratings', 'dropdown'])->flush();

            return $category->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $category = RatingCategory::withCount('ratingScores')->findOrFail($id);

            if ($category->rating_scores_count > 0) {
                throw new \Exception('Kategori tidak dapat dihapus karena masih digunakan dalam penilaian (rating) untuk unit.');
            }

            $category->delete();

            Cache::tags(['ratings', 'dropdown'])->flush();

            return true;
        });
    }

    public function toggleActive(int $id): RatingCategory
    {
        return DB::transaction(function () use ($id) {
            $category = RatingCategory::findOrFail($id);

            $category->update(['is_active' => !$category->is_active]);

            Cache::tags(['ratings', 'dropdown'])->flush();

            return $category->fresh();
        });
    }

    public function reorder(array $orders): bool
    {
        return DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                RatingCategory::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }

            Cache::tags(['ratings', 'dropdown'])->flush();

            return true;
        });
    }
}
