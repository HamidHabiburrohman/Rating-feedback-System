<?php

namespace App\Observers\Admin;

use App\Models\RatingCategory;
use App\Models\ModerationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RatingCategoryObserver
{
    public function created(RatingCategory $category): void
    {
        $this->clearCache();
        $this->logActivity('created', $category);
    }

    public function updated(RatingCategory $category): void
    {
        $this->clearCache();
        
        if ($category->isDirty('is_active')) {
            $this->logActivity('status_changed', $category, [
                'old' => $category->getOriginal('is_active'),
                'new' => $category->is_active,
            ]);
        }

        if ($category->isDirty('sort_order')) {
            $this->logActivity('order_changed', $category, [
                'old' => $category->getOriginal('sort_order'),
                'new' => $category->sort_order,
            ]);
        }
    }

    public function deleted(RatingCategory $category): void
    {
        $this->clearCache();
        
        if ($category->isForceDeleting()) {
            $this->logActivity('force_deleted', $category);
        } else {
            $this->logActivity('deleted', $category);
        }
    }

    public function restored(RatingCategory $category): void
    {
        $this->clearCache();
        $this->logActivity('restored', $category);
    }

    public function forceDeleted(RatingCategory $category): void
    {
        $this->clearCache();
        $this->logActivity('force_deleted', $category);
    }

    public function saving(RatingCategory $category): void
    {
        if (empty($category->slug) && !empty($category->name)) {
            $category->slug = Str::slug($category->name);
        }
    }

    protected function clearCache(): void
    {
        Cache::forget('rating_categories.active');
        Cache::forget('rating_categories.all');
        Cache::forget('ratings.filter_data');
        Cache::forget('dashboard.stats.rating_categories');
    }

    protected function logActivity(string $action, RatingCategory $category, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'rating_category_' . $action,
                'target_type' => 'rating_category',
                'target_id' => $category->id,
                'metadata' => json_encode(array_merge([
                    'category_name' => $category->name,
                    'category_slug' => $category->slug,
                    'is_active' => $category->is_active,
                ], $additional)),
            ]);

            Log::info('RatingCategory ' . $action, [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'admin_id' => Auth::id(),
                'additional' => $additional,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log rating category activity: ' . $e->getMessage());
        }
    }
}