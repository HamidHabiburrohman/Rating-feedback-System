<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitType\StoreUnitTypeRequest;
use App\Http\Requests\Admin\UnitType\UpdateUnitTypeRequest;
use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitType::withCount('units');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $sort = $request->input('sort', 'sort_order');
        $order = $request->input('order', 'asc');

        if (in_array($sort, ['name', 'created_at', 'sort_order'])) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('sort_order')->orderBy('name');
        }

        $perPage = $request->input('per_page', 10);
        $totalUnits = UnitType::count();
        $hidePerPage = $totalUnits <= 10;
        $unitTypes = $query->paginate($perPage)->withQueryString();

        return view('admin.unit-type.index', compact('unitTypes', 'hidePerPage'));
    }

    public function create()
    {
        return view('admin.unit-type.create');
    }

    public function store(StoreUnitTypeRequest $request)
    {
        try {
            UnitType::create($request->validated());
            return redirect()->route('admin.unit-types.index')->with('success', 'Tipe unit berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat tipe unit: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $unitType = UnitType::withCount('units')->findOrFail($id);
        return view('admin.unit-type.show', compact('unitType'));
    }

    public function edit($id)
    {
        $unitType = UnitType::findOrFail($id);
        return view('admin.unit-type.edit', compact('unitType'));
    }

    public function update(UpdateUnitTypeRequest $request, $id)
    {
        try {
            $unitType = UnitType::findOrFail($id);
            $unitType->update($request->validated());
            return redirect()->route('admin.unit-types.index')->with('success', 'Tipe unit berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui tipe unit: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $unitType = UnitType::findOrFail($id);

            if ($unitType->units()->exists()) {
                return redirect()->route('admin.unit-types.index')
                    ->with('error', 'Tidak dapat menghapus tipe unit karena masih memiliki unit terkait');
            }

            $unitType->delete();

            return redirect()->route('admin.unit-types.index')
                ->with('success', 'Tipe unit berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-types.index')
                ->with('error', 'Gagal menghapus tipe unit: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $unitType = UnitType::findOrFail($id);
            $newStatus = !$unitType->is_active;
            $unitType->update(['is_active' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Status tipe unit berhasil diubah',
                'data' => [
                    'id' => $unitType->id,
                    'is_active' => $newStatus,
                    'status_text' => $newStatus ? 'Aktif' : 'Nonaktif',
                    'status_class' => $newStatus ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Toggle status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:unit_types,id'
        ]);

        foreach ($request->ids as $index => $id) {
            UnitType::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil diperbarui'
        ]);
    }
}