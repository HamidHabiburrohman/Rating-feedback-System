<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Unit\StoreUnitRequest;
use App\Http\Requests\Admin\Unit\UpdateUnitRequest;
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
        $units = $this->unitService->getUnits($request->all());
        $typeNames = $this->unitService->getUnitTypeNamesForFilter();

        $unitTypes = $this->unitService->getAllUnitTypesForForm();

        return view('admin.units.index', compact('units', 'typeNames', 'unitTypes'));
    }

    public function create()
    {
        $unitTypes = $this->unitService->getAllUnitTypesForForm();
        return view('admin.units.create', compact('unitTypes'));
    }

    public function store(StoreUnitRequest $request)
    {
        try {
            $this->unitService->createUnit($request->validated(), $request->file('foto_unit'));
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat unit: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $unit = $this->unitService->findUnit($id);
        return view('admin.units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = $this->unitService->findUnit($id);
        $unitTypes = $this->unitService->getAllUnitTypesForForm();

        return view('admin.units.edit', compact('unit', 'unitTypes'));
    }

    public function update(UpdateUnitRequest $request, $id)
    {
        try {
            $this->unitService->updateUnit(
                $id,
                $request->validated(),
                $request->file('foto_unit'),
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
            $this->unitService->deleteUnit($id);
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

            $this->unitService->updateUnitStatus($id, $request->status);

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