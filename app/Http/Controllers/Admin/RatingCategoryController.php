<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RatingCategory\StoreRatingCategoryRequest;
use App\Http\Requests\Admin\RatingCategory\UpdateRatingCategoryRequest;
use App\Services\Admin\RatingCategoryService;
use App\Models\Feedback\RatingCategory;
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
        $this->authorize('viewAny', RatingCategory::class);

        try {
            $categories = $this->service->getAll($request->all());

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.rating-categories.partials.rows', ['categories' => $categories])->render(),
                    'pagination' => view('admin.rating-categories.partials.pagination', ['paginator' => $categories])->render()
                ]);
            }

            return view('admin.rating-categories.index', compact('categories'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
            }

            session()->flash('error', 'Gagal memuat data: ' . $e->getMessage());
            return view('admin.rating-categories.index', ['categories' => collect()]);
        }
    }

    public function create()
    {
        $this->authorize('create', RatingCategory::class);

        return view('admin.rating-categories.create');
    }

    public function store(StoreRatingCategoryRequest $request)
    {
        $this->authorize('create', RatingCategory::class);

        try {
            $category = $this->service->create($request->validated());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil ditambahkan',
                    'data' => $category
                ]);
            }

            return redirect()->route('admin.rating-categories.index')
                ->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'Gagal menambahkan: ' . $e->getMessage());
        }
    }

    public function update(UpdateRatingCategoryRequest $request, int $id)
    {
        $category = RatingCategory::findOrFail($id);
        $this->authorize('update', $category);

        try {
            $this->service->update($id, $request->validated());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil diperbarui'
                ]);
            }

            return redirect()->route('admin.rating-categories.index')
                ->with('success', 'Kategori berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        $category = RatingCategory::findOrFail($id);
        $this->authorize('delete', $category);

        try {
            $this->service->delete($id);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.rating-categories.index')
                ->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function toggleActive(Request $request, int $id)
    {
        $category = RatingCategory::findOrFail($id);
        $this->authorize('update', $category);

        try {
            $this->service->toggleActive($id);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 422);
        }
    }

    public function reorder(Request $request)
    {
        $this->authorize('update', RatingCategory::class);

        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:rating_categories,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            $this->service->reorder($request->orders);

            return response()->json([
                'success' => true,
                'message' => 'Urutan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengurutkan: ' . $e->getMessage()
            ], 422);
        }
    }

    public function active()
    {
        try {
            $categories = $this->service->getActive();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }
}
