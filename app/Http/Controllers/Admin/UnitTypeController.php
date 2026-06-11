<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UnitTypeService;
use App\Models\Unit\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    protected UnitTypeService $service;

    public function __construct(UnitTypeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', UnitType::class);
        
        try {
            $types = $this->service->getAll($request->all());
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.unit-types.partials.rows', ['types' => $types])->render()
                ]);
            }
            
            return view('admin.unit-types.index', compact('types'));
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-types.index')
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create', UnitType::class);
        return view('admin.unit-types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', UnitType::class);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:unit_types,name',
            'slug' => 'nullable|string|max:255|unique:unit_types,slug',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        
        try {
            $type = $this->service->create($request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipe unit berhasil ditambahkan',
                    'data' => $type
                ]);
            }
            
            return redirect()->route('admin.unit-types.index')
                ->with('success', 'Tipe unit berhasil ditambahkan');
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
            $type = $this->service->findById($id);
            $this->authorize('view', $type);
            return view('admin.unit-types.show', compact('type'));
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-types.index')
                ->with('error', 'Tipe unit tidak ditemukan');
        }
    }

    public function edit(int $id)
    {
        try {
            $type = $this->service->findById($id);
            $this->authorize('update', $type);
            return view('admin.unit-types.edit', compact('type'));
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-types.index')
                ->with('error', 'Tipe unit tidak ditemukan');
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $type = UnitType::findOrFail($id);
            $this->authorize('update', $type);
            
            $request->validate([
                'name' => 'required|string|max:255|unique:unit_types,name,' . $id,
                'slug' => 'nullable|string|max:255|unique:unit_types,slug,' . $id,
                'icon' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
            ]);
            
            $this->service->update($id, $request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipe unit berhasil diperbarui'
                ]);
            }
            
            return redirect()->route('admin.unit-types.index')
                ->with('success', 'Tipe unit berhasil diperbarui');
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
            $type = UnitType::findOrFail($id);
            $this->authorize('delete', $type);
            
            $this->service->delete($id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipe unit berhasil dihapus'
                ]);
            }
            
            return redirect()->route('admin.unit-types.index')
                ->with('success', 'Tipe unit berhasil dihapus');
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

    public function reorder(Request $request)
    {
        try {
            $this->authorize('update', UnitType::class);
            
            $request->validate([
                'orders' => 'required|array',
                'orders.*.id' => 'required|exists:unit_types,id',
                'orders.*.sort_order' => 'required|integer|min:0',
            ]);
            
            $this->service->reorder($request->orders);
            
            return response()->json([
                'success' => true,
                'message' => 'Urutan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengurutkan: ' . $e->getMessage()
            ], 500);
        }
    }
}