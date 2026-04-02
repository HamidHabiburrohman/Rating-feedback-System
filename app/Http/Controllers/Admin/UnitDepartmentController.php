<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitDepartment\StoreUnitDepartmentRequest;
use App\Http\Requests\Admin\UnitDepartment\UpdateUnitDepartmentRequest;
use App\Services\Admin\UnitDepartmentService;
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
        $filters = $request->only(['search', 'status', 'sort', 'order', 'per_page']);
        $departments = $this->service->getPaginated($filters);
        $stats = $this->service->getStats();
        $filterData = $this->service->getForFilter();

        return view('admin.unit-departments.index', compact('departments', 'stats', 'filterData'));
    }

    public function create()
    {
        return view('admin.unit-departments.create');
    }

    public function store(StoreUnitDepartmentRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('admin.unit-departments.index')->with('success', 'Departemen unit berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat departemen: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            return view('admin.unit-departments.show', ['department' => $this->service->find($id)]);
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-departments.index')->with('error', 'Departemen tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            return view('admin.unit-departments.edit', ['department' => $this->service->find($id)]);
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-departments.index')->with('error', 'Departemen tidak ditemukan');
        }
    }

    public function update(UpdateUnitDepartmentRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->validated());
            return redirect()->route('admin.unit-departments.index')->with('success', 'Departemen unit berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui departemen: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('admin.unit-departments.index')->with('success', 'Departemen unit berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.unit-departments.index')->with('error', $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->toggleStatus($id)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getStats()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
        }
    }
}