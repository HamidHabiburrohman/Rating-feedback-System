<?php

namespace App\Http\Controllers\Admin\Unit;

use App\Http\Controllers\Controller;
use App\Services\Admin\Unit\UnitService;
use App\Models\Unit\Unit;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UnitController extends Controller
{
    protected UnitService $service;

    public function __construct(UnitService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Unit::class);

        try {
            $filters = $request->only(['search', 'type', 'department', 'status', 'per_page', 'sort', 'order']);
            $units = $this->service->getFilteredUnits($filters);
            $unitTypes = $this->service->getUnitTypesForFilter();
            $departments = $this->service->getDepartmentsForFilter();

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.units.partials.rows', compact('units'))->render(),
                    'pagination' => view('admin.units.partials.pagination', ['paginator' => $units])->render()
                ]);
            }

            return view('admin.units.index', compact('units', 'unitTypes', 'departments'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data unit: ' . $e->getMessage()
                ], 500);
            }

            $emptyUnits = new LengthAwarePaginator([], 0, 10);

            return view('admin.units.index', [
                'units' => $emptyUnits,
                'unitTypes' => [],
                'departments' => []
            ])->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create', Unit::class);
        $unitTypes = $this->service->getUnitTypesForFilter();
        $unitDepartments = $this->service->getDepartmentsForFilter();
        return view('admin.units.create', compact('unitTypes', 'unitDepartments'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Unit::class);
        try {
            $unit = $this->service->create($request->all());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit berhasil ditambahkan',
                    'redirect' => route('admin.units.show', $unit->id)
                ]);
            }
            return redirect()->route('admin.units.show', $unit->id)->with('success', 'Unit berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambahkan unit: ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Gagal menambahkan unit: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->service->getDetail($id);
            $this->authorize('view', $data['unit']);
            return view('admin.units.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.units.index')->with('error', 'Unit tidak ditemukan');
        }
    }

    public function edit(int $id)
    {
        try {
            $data = $this->service->getEditData($id);
            $this->authorize('update', $data['unit']);
            return view('admin.units.edit', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.units.index')->with('error', 'Gagal memuat form edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $this->authorize('update', $unit);
            $this->service->update($id, $request->all());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Unit berhasil diperbarui']);
            }
            return redirect()->route('admin.units.show', $id)->with('success', 'Unit berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $this->authorize('delete', $unit);
            $this->service->delete($id);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Unit berhasil dihapus']);
            }
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function trashed(Request $request)
    {
        $this->authorize('viewAny', Unit::class);
        $units = $this->service->getTrashed($request->all());
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.units.partials.trashed-rows', compact('units'))->render(),
                'pagination' => view('admin.units.partials.pagination', ['paginator' => $units])->render()
            ]);
        }
        return view('admin.units.trashed', compact('units'));
    }

    public function restore(Request $request, int $id)
    {
        try {
            $this->service->restore($id);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Unit berhasil dipulihkan']);
            }
            return back()->with('success', 'Unit berhasil dipulihkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memulihkan: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal memulihkan: ' . $e->getMessage());
        }
    }
}
