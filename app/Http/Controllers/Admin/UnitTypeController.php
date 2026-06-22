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
                    'html' => view('admin.unit-types.partials.rows', ['types' => $types])->render(),
                    'pagination' => view('admin.unit-types.partials.pagination', ['paginator' => $types])->render()
                ]);
            }

            return view('admin.unit-types.index', compact('types'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
            }
            session()->flash('error', 'Gagal memuat data: ' . $e->getMessage());
            return view('admin.unit-types.index', ['types' => collect()]);
        }
    }

    public function create()
    {
        $this->authorize('create', UnitType::class);
        $icons = app(\App\Services\Admin\IconService::class)->getIconPreviews();
        return view('admin.unit-types.create', compact('icons'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', UnitType::class);
        $request->validate([
            'name' => 'required|string|max:255|unique:unit_types,name',
            'slug' => 'nullable|string|max:255|unique:unit_types,slug',
            'icon_key' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
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
        $type = $this->service->findById($id);
        $this->authorize('view', $type);
        return view('admin.unit-types.show', compact('type'));
    }

    public function edit(int $id)
    {
        $type = $this->service->findById($id);
        $icons = app(\App\Services\Admin\IconService::class)->getIconPreviews();
        $this->authorize('update', $type);
        return view('admin.unit-types.edit', compact('type', 'icons'));
    }

    public function update(Request $request, int $id)
    {
        $type = UnitType::findOrFail($id);
        $this->authorize('update', $type);
        $request->validate([
            'name' => 'required|string|max:255|unique:unit_types,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:unit_types,slug,' . $id,
            'icon_key' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
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
        $type = UnitType::findOrFail($id);
        $this->authorize('delete', $type);

        try {
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
        $this->authorize('update', UnitType::class);
        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil diperbarui'
        ]);
    }
}
