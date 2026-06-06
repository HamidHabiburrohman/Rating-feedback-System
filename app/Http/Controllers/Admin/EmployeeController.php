<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\AssignToUnitRequest;
use App\Http\Requests\Admin\Employee\StoreEmployeeRequest;
use App\Http\Requests\Admin\Employee\UpdateEmployeeRequest;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->getAll();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = $this->employeeService->create($request->validated());
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully');
    }

    public function show($id)
    {
        $employee = $this->employeeService->findById($id);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with('error', 'Employee not found');
        }
        $assignments = $this->employeeService->getAssignments($id);
        $assignedUnits = $this->employeeService->getAssignedUnits($id);
        return view('admin.employees.show', compact('employee', 'assignments', 'assignedUnits'));
    }

    public function edit($id)
    {
        $employee = $this->employeeService->findById($id);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with('error', 'Employee not found');
        }
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $updated = $this->employeeService->update($id, $request->validated());
        if (!$updated) {
            return redirect()->back()->with('error', 'Employee not found');
        }
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy($id)
    {
        $deleted = $this->employeeService->delete($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Employee deleted successfully']);
    }

    public function restore($id)
    {
        $restored = $this->employeeService->restore($id);
        if (!$restored) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Employee restored successfully']);
    }

    public function assignToUnit(AssignToUnitRequest $request)
    {
        $data = $request->validated();
        $assignment = $this->employeeService->assignToUnit(
            $data['employee_id'],
            $data['unit_id'],
            auth('admin')->id(),
            $data['role_in_unit'] ?? null
        );
        return response()->json(['success' => true, 'data' => $assignment]);
    }

    public function removeFromUnit($employeeId, $unitId)
    {
        $removed = $this->employeeService->removeFromUnit($employeeId, $unitId);
        if (!$removed) {
            return response()->json(['success' => false, 'message' => 'Assignment not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Employee removed from unit']);
    }
}