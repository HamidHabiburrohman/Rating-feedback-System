<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Services\Admin\Report\ReportCategoryService;
use App\Models\Report\ReportCategory;
use Illuminate\Http\Request;

class ReportCategoryController extends Controller
{
    protected ReportCategoryService $service;

    public function __construct(ReportCategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', ReportCategory::class);
        try {
            $filters = $request->only(['search', 'status', 'page', 'sort', 'order', 'per_page']);
            $categories = $this->service->getAll($filters);

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.report-categories.partials.rows', compact('categories'))->render(),
                    'pagination' => view('admin.report-categories.partials.pagination', ['paginator' => $categories])->render()
                ]);
            }

            return view('admin.report-categories.index', compact('categories'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
            }
            logger()->error('Error fetching report categories: ' . $e->getMessage());
            return redirect()->route('admin.report-categories.index')
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', ReportCategory::class);
        $request->validate([
            'name' => 'required|string|max:255|unique:report_categories,name',
            'slug' => 'nullable|string|max:255|unique:report_categories,slug',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $category = $this->service->create($request->all());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan', 'data' => $category]);
            }

            return redirect()->route('admin.report-categories.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Gagal menambahkan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $category = ReportCategory::findOrFail($id);
        $this->authorize('update', $category);
        $request->validate([
            'name' => 'required|string|max:255|unique:report_categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:report_categories,slug,' . $id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $this->service->update($id, $request->all());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui']);
            }

            return redirect()->route('admin.report-categories.index')->with('success', 'Kategori berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        $category = ReportCategory::findOrFail($id);
        $this->authorize('delete', $category);

        try {
            $this->service->delete($id);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus']);
            }

            return redirect()->route('admin.report-categories.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function toggleActive(Request $request, int $id)
    {
        $category = ReportCategory::findOrFail($id);
        $this->authorize('update', $category);

        try {
            $this->service->toggleActive($id);
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}
