<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\AssignedUnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected AssignedUnitService $service;

    public function __construct(AssignedUnitService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $filters = $request->only(['search', 'per_page']);
            $units = $this->service->getAssignedUnits($employee->id, $filters);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('employee.units.partials.rows', compact('units'))->render(),
                    'pagination' => view('employee.units.partials.pagination', ['paginator' => $units])->render()
                ]);
            }
            
            return view('employee.units.index', compact('units'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data unit: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('employee.dashboard.index')
                ->with('error', 'Gagal memuat data unit: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $data = $this->service->getUnitDetail($employee->id, $id);
            return view('employee.units.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('employee.units.index')
                ->with('error', $e->getMessage());
        }
    }

    public function edit(int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $data = $this->service->getUnitDetail($employee->id, $id);
            return view('employee.units.edit', $data);
        } catch (\Exception $e) {
            return redirect()->route('employee.units.index')
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        // Employee hanya bisa view, tidak bisa update unit
        // Method ini ada untuk kompatibilitas route, tapi return 403
        abort(403, 'Employee tidak memiliki akses untuk mengupdate unit');
    }

    public function ratingCategories(int $unitId)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $categories = $this->service->getRatingCategories($unitId);
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat kategori rating: ' . $e->getMessage()
            ], 500);
        }
    }
}