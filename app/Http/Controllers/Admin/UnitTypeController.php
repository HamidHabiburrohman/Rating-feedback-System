<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitType\StoreUnitTypeRequest;
use App\Http\Requests\Admin\UnitType\UpdateUnitTypesRequest;
use App\Http\Requests\Admin\UnitType\ReorderRequest;
use App\Services\Admin\UnitTypeService;
use App\Services\Admin\IconService;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    protected UnitTypeService $service;
    protected IconService $iconService;

    public function __construct(UnitTypeService $service, IconService $iconService)
    {
        $this->service = $service;
        $this->iconService = $iconService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'sort', 'order', 'per_page']);

        return view('admin.unit-types.index', [
            'unitTypes' => $this->service->getPaginated($filters),
            'filterData' => $this->service->getFilterData(),
            'stats' => $this->service->getStats()
        ]);
    }

    public function create()
    {
        return view('admin.unit-types.create', [
            'icons' => $this->iconService->getIconOptions(),
            'iconPreviews' => $this->iconService->getIconPreviews()
        ]);
    }

    public function store(StoreUnitTypeRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.unit-types.index')
            ->with('success', 'Tipe unit berhasil dibuat');
    }

    public function show($id)
    {
        return view('admin.unit-types.show', [
            'unitType' => $this->service->findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('admin.unit-types.edit', [
            'unitType' => $this->service->findOrFail($id),
            'icons' => $this->iconService->getIconOptions(),
            'iconPreviews' => $this->iconService->getIconPreviews()
        ]);
    }

    public function update(UpdateUnitTypesRequest $request, $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('admin.unit-types.index')
            ->with('success', 'Tipe unit berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.unit-types.index')
            ->with('success', 'Tipe unit berhasil dihapus');
    }
}