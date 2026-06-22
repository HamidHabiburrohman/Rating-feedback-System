<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UnitDepartmentService;
use App\Models\Unit\UnitDepartment;
use Illuminate\Http\Request;

class UnitDepartmentController extends Controller
{
    protected UnitDepartmentService $service;

    public function __construct(UnitDepartmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', UnitDepartment::class);

        try {
            $departments = $this->service->getAll($request->all());

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.unit-departments.partials.rows', ['departments' => $departments])->render(),
                    'pagination' => view('admin.unit-departments.partials.pagination', ['paginator' => $departments])->render()
                ]);
            }

            return view('admin.unit-departments.index', compact('departments'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
            }
            session()->flash('error', 'Gagal memuat data: ' . $e->getMessage());
            return view('admin.unit-departments.index', ['departments' => collect()]);
        }
    }

    public function create()
    {
        $this->authorize('create', UnitDepartment::class);
        return view('admin.unit-departments.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', UnitDepartment::class);
        $request->validate([
            'name' => 'required|string|max:255|unique:unit_departments,name',
            'slug' => 'nullable|string|max:255|unique:unit_departments,slug',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $department = $this->service->create($request->all());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Departemen berhasil ditambahkan',
                    'data' => $department
                ]);
            }

            return redirect()->route('admin.unit-departments.index')
                ->with('success', 'Departemen berhasil ditambahkan');
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

    public function show(int $id)
    {
        try {
            $department = $this->service->findById($id);
            $this->authorize('view', $department);
            return view('admin.unit-departments.show', compact('department'));
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-departments.index')
                ->with('error', 'Departemen tidak ditemukan');
        }
    }

    public function edit(int $id)
    {
        try {
            $department = $this->service->findById($id);
            $this->authorize('update', $department);
            return view('admin.unit-departments.edit', compact('department'));
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-departments.index')
                ->with('error', 'Departemen tidak ditemukan');
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $department = UnitDepartment::findOrFail($id);
            $this->authorize('update', $department);
            $request->validate([
                'name' => 'required|string|max:255|unique:unit_departments,name,' . $id,
                'slug' => 'nullable|string|max:255|unique:unit_departments,slug,' . $id,
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
            ]);

            $this->service->update($id, $request->all());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Departemen berhasil diperbarui'
                ]);
            }

            return redirect()->route('admin.unit-departments.index')
                ->with('success', 'Departemen berhasil diperbarui');
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
        try {
            $department = UnitDepartment::findOrFail($id);
            $this->authorize('delete', $department);
            $this->service->delete($id);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Departemen berhasil dihapus'
                ]);
            }

            return redirect()->route('admin.unit-departments.index')
                ->with('success', 'Departemen berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, int $id)
    {
        try {
            $department = UnitDepartment::findOrFail($id);
            $this->authorize('update', $department);
            $this->service->toggleStatus($id);
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats()
    {
        $this->authorize('viewAny', UnitDepartment::class);
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getStats()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }
}
