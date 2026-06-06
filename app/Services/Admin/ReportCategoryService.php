<?php

namespace App\Services\Admin;

use App\Models\Reports\ReportCategory;
use Illuminate\Database\Eloquent\Collection;

class ReportCategoryService
{
    public function getAll(): Collection
    {
        return ReportCategory::all();
    }

    public function getActive(): Collection
    {
        return ReportCategory::where('is_active', true)->get();
    }

    public function findById(int $id): ?ReportCategory
    {
        return ReportCategory::find($id);
    }

    public function findBySlug(string $slug): ?ReportCategory
    {
        return ReportCategory::where('slug', $slug)->first();
    }

    public function create(array $data): ReportCategory
    {
        return ReportCategory::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = $this->findById($id);
        if (!$category) {
            return false;
        }
        return $category->update($data);
    }

    public function delete(int $id): bool
    {
        $category = $this->findById($id);
        if (!$category) {
            return false;
        }
        return $category->delete();
    }

    public function toggleActive(int $id): bool
    {
        $category = $this->findById($id);
        if (!$category) {
            return false;
        }
        $category->is_active = !$category->is_active;
        return $category->save();
    }
}