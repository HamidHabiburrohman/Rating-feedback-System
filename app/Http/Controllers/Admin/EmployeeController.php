<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Employee;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected EmployeeService $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Employee::class);
        
        try {
            $filters = $request->only(['search', 'unit_id', 'status', 'sort', 'per_page']);
            $employees = $this->service->getFilteredEmployees($filters);
            $units = $this->service->getUnitsForFilter();

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.employees.partials.rows', compact('employees'))->render(),
                    'pagination' => view('admin.employees.partials.pagination', ['paginator' => $employees])->render()
                ]);
            }

            return view('admin.employees.index', compact('employees', 'units'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.employees.index')->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create', Employee::class);
        $data = $this->service->getFormData();
        return view('admin.employees.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Employee::class);
        try {
            $employee = $this->service->create($request->all());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Karyawan berhasil ditambahkan',
                    'redirect' => route('admin.employees.show', $employee->id)
                ]);
            }
            return redirect()->route('admin.employees.show', $employee->id)->with('success', 'Karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Gagal menambahkan: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->service->getDetail($id);
            $this->authorize('view', $data['employee']);
            return view('admin.employees.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.employees.index')->with('error', 'Karyawan tidak ditemukan');
        }
    }

    public function edit(int $id)
    {
        try {
            $data = $this->service->getEditData($id);
            $this->authorize('update', $data['employee']);
            return view('admin.employees.edit', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.employees.index')->with('error', 'Karyawan tidak ditemukan');
        }
    }

    public function update(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);
        $this->authorize('update', $employee);
        try {
            $this->service->update($id, $request->all());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Karyawan berhasil diperbarui']);
            }
            return redirect()->route('admin.employees.show', $id)->with('success', 'Karyawan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);
        $this->authorize('delete', $employee);
        try {
            $this->service->delete($id);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Karyawan berhasil dihapus']);
            }
            return redirect()->route('admin.employees.index')->with('success', 'Karyawan berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function restore(Request $request, int $id)
    {
        $this->authorize('restore', Employee::class);
        try {
            $this->service->restore($id);
            return response()->json(['success' => true, 'message' => 'Karyawan berhasil dipulihkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memulihkan: ' . $e->getMessage()], 500);
        }
    }

    public function assignToUnit(Request $request, int $id)
    {
        $this->authorize('assignToUnit', Employee::class);
        $request->validate(['unit_id' => 'required|exists:units,id', 'role_in_unit' => 'nullable|string|max:100']);
        try {
            $this->service->assignToUnit($id, $request->unit_id, $request->role_in_unit);
            return response()->json(['success' => true, 'message' => 'Berhasil ditugaskan ke unit']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menugaskan: ' . $e->getMessage()], 500);
        }
    }

    public function removeFromUnit(Request $request, int $id)
    {
        $this->authorize('assignToUnit', Employee::class);
        $request->validate(['unit_id' => 'required|exists:units,id']);
        try {
            $this->service->removeFromUnit($id, $request->unit_id);
            return response()->json(['success' => true, 'message' => 'Berhasil dihapus dari unit']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function getAssignedUnits(int $id)
    {
        try {
            $units = $this->service->getAssignedUnits($id);
            return response()->json(['success' => true, 'data' => $units]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat data'], 500);
        }
    }
}