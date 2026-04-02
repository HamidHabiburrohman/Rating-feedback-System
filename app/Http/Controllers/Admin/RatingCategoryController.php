<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RatingCategory\StoreRatingCategoryRequest;
use App\Http\Requests\Admin\RatingCategory\UpdateRatingCategoryRequest;
use App\Http\Requests\Admin\RatingCategory\ReorderCategoryRequest;
use App\Services\Admin\RatingCategoryService;
use Illuminate\Http\Request;

class RatingCategoryController extends Controller
{
    protected RatingCategoryService $service;

    public function __construct(RatingCategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'sort', 'order', 'per_page']);
        $categories = $this->service->getPaginated($filters);
        $stats = $this->service->getStats();
        $filterData = $this->service->getForFilter();

        return view('admin.rating-categories.index', compact('categories', 'stats', 'filterData'));
    }

    public function create()
    {
        return view('admin.rating-categories.create');
    }

    public function store(StoreRatingCategoryRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('admin.rating-categories.index')->with('success', 'Kategori rating berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat kategori: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = $this->service->find((int) $id);
            return view('admin.rating-categories.show', ['category' => $category]);
        } catch (\Exception $e) {
            return redirect()->route('admin.rating-categories.index')->with('error', 'Kategori tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $category = $this->service->find((int) $id);
            return view('admin.rating-categories.edit', ['category' => $category]);
        } catch (\Exception $e) {
            return redirect()->route('admin.rating-categories.index')->with('error', 'Kategori tidak ditemukan');
        }
    }

    public function update(UpdateRatingCategoryRequest $request, $id)
    {
        try {
            $this->service->update((int) $id, $request->validated());
            return redirect()->route('admin.rating-categories.index')->with('success', 'Kategori rating berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete((int) $id);
            return redirect()->route('admin.rating-categories.index')->with('success', 'Kategori rating berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.rating-categories.index')->with('error', $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->toggleActive((int) $id)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }

    public function reorder(ReorderCategoryRequest $request)
    {
        try {
            $this->service->reorder($request->categories);
            return response()->json(['success' => true, 'message' => 'Urutan kategori berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah urutan: ' . $e->getMessage()], 500);
        }
    }

    public function getActive()
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getActiveCategories()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil kategori aktif'], 500);
        }
    }

    public function stats()
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getStats()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
        }
    }

    public function seedDefaults()
    {
        try {
            $this->service->seedDefaultCategories();
            return redirect()->route('admin.rating-categories.index')->with('success', 'Kategori default berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan kategori default: ' . $e->getMessage());
        }
    }

    public function validateForRating(Request $request)
    {
        try {
            $request->validate(['category_ids' => 'required|array', 'category_ids.*' => 'exists:rating_categories,id']);
            $result = $this->service->validateCategoriesForRating($request->category_ids);
            return $result['valid']
                ? response()->json(['success' => true, 'message' => 'Semua kategori valid'])
                : response()->json(['success' => false, 'message' => 'Kategori rating tidak lengkap', 'missing' => $result['missing']], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memvalidasi kategori'], 500);
        }
    }

    public function export()
    {
        try {
            return $this->service->export();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}