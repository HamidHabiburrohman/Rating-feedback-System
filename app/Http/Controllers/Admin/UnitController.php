<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UnitService;
use App\Models\Unit\Unit;
use Illuminate\Http\Request;

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
            $filters = $request->only(['search', 'type', 'department', 'status', 'per_page', 'sort']);
            $units = $this->service->getFilteredUnits($filters);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.units.partials.rows', compact('units'))->render(),
                    'pagination' => view('admin.units.partials.pagination', ['paginator' => $units])->render()
                ]);
            }
            
            return view('admin.units.index', compact('units'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data unit: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.units.index')
                ->with('error', 'Gagal memuat data unit: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create', Unit::class);
        
        $data = $this->service->getFormData();
        return view('admin.units.create', $data);
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
            
            return redirect()->route('admin.units.show', $unit->id)
                ->with('success', 'Unit berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan unit: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal menambahkan unit: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->service->getDetail($id);
            $this->authorize('view', $data['unit']);
            return view('admin.units.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.units.index')
                ->with('error', 'Unit tidak ditemukan');
        }
    }

    public function edit(int $id)
    {
        try {
            $data = $this->service->getEditData($id);
            $this->authorize('update', $data['unit']);
            return view('admin.units.edit', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.units.index')
                ->with('error', 'Unit tidak ditemukan');
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $this->authorize('update', $unit);
            
            $updated = $this->service->update($id, $request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit berhasil diperbarui'
                ]);
            }
            
            return redirect()->route('admin.units.show', $id)
                ->with('success', 'Unit berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui unit: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal memperbarui unit: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $this->authorize('delete', $unit);
            
            $this->service->delete($id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit berhasil dihapus'
                ]);
            }
            
            return redirect()->route('admin.units.index')
                ->with('success', 'Unit berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus unit: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menghapus unit: ' . $e->getMessage());
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
                return response()->json([
                    'success' => true,
                    'message' => 'Unit berhasil dipulihkan'
                ]);
            }
            
            return back()->with('success', 'Unit berhasil dipulihkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memulihkan unit: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memulihkan unit: ' . $e->getMessage());
        }
    }

    public function forceDelete(Request $request, int $id)
    {
        try {
            $this->service->forceDelete($id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit berhasil dihapus permanen'
                ]);
            }
            
            return back()->with('success', 'Unit berhasil dihapus permanen');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus permanen: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menghapus permanen: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, int $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $this->authorize('update', $unit);
            
            $this->service->toggleStatus($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Status unit berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncFacilities(Request $request, int $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $this->authorize('update', $unit);
            
            $request->validate([
                'facilities' => 'nullable|array',
                'facilities.*' => 'exists:facilities,id'
            ]);
            
            $this->service->syncFacilities($id, $request->facilities ?? []);
            
            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil disinkronkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:units,id']);
            $this->authorize('delete', Unit::class);
            
            $count = $this->service->bulkDelete($request->ids);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus massal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
            $count = $this->service->bulkRestore($request->ids);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil dipulihkan"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan massal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
            $count = $this->service->bulkForceDelete($request->ids);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil dihapus permanen"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen massal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkActivate(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:units,id']);
            $count = $this->service->bulkActivate($request->ids);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil diaktifkan"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan massal: ' . $e->getMessage()
            ], 500);
        }
    }
}