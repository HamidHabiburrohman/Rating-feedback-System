<?php

namespace App\Services\Admin;

use App\Models\Feedback\RatingCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RatingCategoryService extends BaseAdminService
{
    public function getAll(array $filters = [])
    {
        $query = RatingCategory::withCount('scores');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query->orderBy('sort_order')->orderBy('name')->get();
    }

    public function getActive(): array
    {
        return Cache::tags(['ratings', 'dropdown'])->remember('active_rating_categories', 86400, function () {
            return RatingCategory::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'description', 'icon'])
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
            $category = RatingCategory::findOrFail($id);

            if ($category->scores()->count() > 0) {
                throw new \Exception('Kategori tidak dapat dihapus karena masih digunakan oleh rating');
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