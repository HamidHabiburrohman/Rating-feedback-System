<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Unit\StoreUnitRequest;
use App\Http\Requests\Admin\Unit\UpdateUnitRequest;
use App\Models\Unit;
use App\Models\UnitType;
use App\Services\Admin\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    public function index(Request $request)
    {
        $query = Unit::with('unitType');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_unit', 'LIKE', "%{$search}%")
                    ->orWhere('kode_unit', 'LIKE', "%{$search}%")
                    ->orWhere('lokasi', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $statusFilters = explode(',', $request->status);
            $query->whereIn('status', $statusFilters);
        }

        if ($request->filled('type')) {
            $typeFilters = explode(',', $request->type);
            $typeIds = UnitType::whereIn('name', $typeFilters)
                ->pluck('id')
                ->toArray();

            if (!empty($typeIds)) {
                $query->whereIn('type_id', $typeIds);
            }
        }

        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($sort, $order);

        $units = $query->paginate($request->input('per_page', 10))
            ->withQueryString();

        $types = UnitType::active()->ordered()->pluck('name');

        return view('admin.units.index', compact('units', 'types'));
    }

    public function create()
    {
        $unitTypes = UnitType::all();
        return view('admin.units.create', compact('unitTypes'));
    }

    public function store(StoreUnitRequest $request)
    {
        try {
            Unit::create($request->validated());
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat unit: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $unit = Unit::with('unitType')->findOrFail($id);
        return view('admin.units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $unitTypes = UnitType::all();
        return view('admin.units.edit', compact('unit', 'unitTypes'));
    }

    public function update(UpdateUnitRequest $request, $id)
    {
        try {
            $this->unitService->updateUnit(
                $id,
                $request->validated(),
                $request->file('foto_unit'), // Pastikan ini
                $request->has('remove_foto') && $request->remove_foto == '1'
            );

            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui unit: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus unit: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:open,full,maintenance,closed'
            ]);

            $unit = Unit::findOrFail($id);
            $unit->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status unit berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function categories($id)
    {
        $categories = $this->unitService->getUnitCategories($id);
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}