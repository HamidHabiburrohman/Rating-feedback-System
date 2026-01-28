<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Unit\StoreUnitRequest;
use App\Http\Requests\Admin\Unit\UpdateUnitRequest;
use App\Models\Unit;
use App\Models\UnitType;
use App\Services\Admin\UnitTypeService;
use App\Services\Admin\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService, UnitTypeService $unitTypeService)
    {
        $this->unitService = $unitService;
        $this->unitTypeService = $unitTypeService;
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
            $statusConditions = [];
            
            if (in_array('OPEN', $statusFilters)) {
                $statusConditions[] = true;
            }
            if (in_array('CLOSED', $statusFilters)) {
                $statusConditions[] = false;
            }
            
            if (!empty($statusConditions)) {
                $query->whereIn('status_aktif', $statusConditions);
            }
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

        $units = $query->latest()
            ->paginate($request->input('per_page', 10))
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
        $unit = $this->unitService->updateUnit($id, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil diperbarui',
            'data' => $unit
        ]);
    }

    public function destroy($id)
    {
        $this->unitService->deleteUnit($id);
        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil dihapus'
        ]);
    }

    public function toggleStatus($id)
    {
        $unit = $this->unitService->toggleUnitStatus($id);
        return response()->json([
            'success' => true,
            'message' => 'Status unit berhasil diubah',
            'data' => $unit
        ]);
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