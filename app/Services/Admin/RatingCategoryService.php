<?php

namespace App\Services\Admin;

use App\Models\RatingCategory;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class RatingCategoryService extends BaseAdminService
{
    protected array $searchableColumns = ['name', 'slug'];
    protected array $filterableColumns = ['is_active'];
    protected string $defaultSort = 'sort_order';
    protected string $defaultOrder = 'asc';

    public function __construct(RatingCategory $ratingCategory)
    {
        $this->model = $ratingCategory;
        parent::__construct();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters)->withCount('ratingScores');
            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('RatingCategoryService::getPaginated error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            
            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? 10,
                1,
                ['path' => request()->url()]
            );
        }
    }

    public function getForFilter(): array
    {
        return [
            'statuses' => [
                '' => 'Semua Status',
                '1' => 'Aktif',
                '0' => 'Nonaktif'
            ]
        ];
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'with_scores' => $this->model->has('ratingScores')->count()
        ];
    }

    public function toggleActive(int $id): array
    {
        $category = $this->find($id);
        $category->is_active = !$category->is_active;
        $category->save();

        $this->logAdminAction('toggle_category', $category, null, [
            'previous' => !$category->is_active,
            'current' => $category->is_active
        ]);

        return $this->formatStatusResponse($category);
    }

    public function reorder(array $categories): bool
    {
        foreach ($categories as $item) {
            $this->model->where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        $this->logAdminAction('reorder_categories', (object)['count' => count($categories)]);

        return true;
    }

    public function getActiveCategories(): array
    {
        return $this->model->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug'])
            ->toArray();
    }

    public function seedDefaultCategories(): void
    {
        $defaults = [
            ['name' => 'Fasilitas', 'slug' => 'facility', 'sort_order' => 1],
            ['name' => 'Pelayanan', 'slug' => 'service', 'sort_order' => 2],
            ['name' => 'Kualitas', 'slug' => 'quality', 'sort_order' => 3],
            ['name' => 'Kebersihan', 'slug' => 'cleanliness', 'sort_order' => 4]
        ];

        foreach ($defaults as $default) {
            $this->model->firstOrCreate(
                ['slug' => $default['slug']],
                array_merge($default, ['is_active' => true])
            );
        }

        $this->logAdminAction('seed_categories', (object)['count' => count($defaults)]);
    }

    public function validateCategoriesForRating(array $categoryIds): array
    {
        $required = $this->model->where('is_active', true)->pluck('id')->toArray();
        $missing = array_diff($required, $categoryIds);

        return [
            'valid' => empty($missing),
            'missing' => $missing
        ];
    }

    public function getMissingCategories(array $categoryIds): array
    {
        $required = $this->model->where('is_active', true)->pluck('name', 'id')->toArray();
        $missing = [];

        foreach ($required as $id => $name) {
            if (!in_array($id, $categoryIds)) {
                $missing[] = ['id' => $id, 'name' => $name];
            }
        }

        return $missing;
    }

    public function export()
    {
        $categories = $this->model->withCount('ratingScores')->orderBy('sort_order')->get();

        $filename = 'rating-categories-export-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Nama Kategori', 'Slug', 'Status', 'Sort Order', 'Jumlah Penggunaan', 'Dibuat Pada']);

            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->name,
                    $category->slug,
                    $category->is_active ? 'Aktif' : 'Nonaktif',
                    $category->sort_order,
                    $category->rating_scores_count,
                    $category->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}