<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\UpdateEmployeeRequest;

use App\Http\Requests\Admin\Empolyee\StoreEmployeeRequest;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request)
    {
        $query = Employee::with('unit');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('bidang', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('telepon', 'LIKE', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $statusFilters = explode(',', $request->status);
            $query->whereIn('status', $statusFilters);
        }
        
        // Unit filter
        if ($request->filled('unit')) {
            $unitFilters = explode(',', $request->unit);
            $query->whereIn('unit_id', $unitFilters);
        }
        
        // Bidang filter
        if ($request->filled('bidang')) {
            $bidangFilters = explode(',', $request->bidang);
            $query->whereIn('bidang', $bidangFilters);
        }
        
        // Sorting
        $sort = $request->input('sort', 'nama');
        $order = $request->input('order', 'asc');
        $query->orderBy($sort, $order);
        
        $employees = $query->paginate($request->input('per_page', 10))
                          ->withQueryString();
        
        $units = Unit::get();
        $bidangs = Employee::select('bidang')->whereNotNull('bidang')->distinct()->pluck('bidang');
        
        return view('admin.employee.index', compact('employees', 'units', 'bidangs'));
    }

    public function create()
    {
        $units = Unit::get();
        return view('admin.employee.create', compact('units'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        try {
            Employee::create($request->validated());
            return redirect()->route('admin.employees.index')->with('success', 'Employee berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan employee: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $employee = Employee::with('unit')->findOrFail($id);
        return view('admin.employee.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::with('unit')->findOrFail($id);
        $units = Unit::get();
        return view('admin.employee.edit', compact('employee', 'units'));
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->update($request->validated());
            return redirect()->route('admin.employees.index')->with('success', 'Data employee berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui employee: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return redirect()->route('admin.employees.index')->with('success', 'Employee berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus employee: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:aktif,cuti,resign'
            ]);
            
            $employee = Employee::findOrFail($id);
            $employee->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status employee berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}