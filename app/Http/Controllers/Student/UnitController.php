<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\UnitService;
use App\Services\Student\RatingService;
use App\Models\Unit\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected UnitService $unitService;
    protected RatingService $ratingService;

    public function __construct(
        UnitService $unitService,
        RatingService $ratingService
    ) {
        $this->unitService = $unitService;
        $this->ratingService = $ratingService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['search', 'type', 'department', 'sort', 'per_page']);
            $units = $this->unitService->getFilteredUnits($filters);
            $types = $this->unitService->getActiveUnitTypes();
            $departments = $this->unitService->getActiveDepartments();
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('student.units.partials.grid', compact('units'))->render(),
                    'pagination' => view('student.units.partials.pagination', ['paginator' => $units])->render()
                ]);
            }
            
            return view('student.units.index', compact('units', 'types', 'departments'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat unit: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('student.dashboard.index')
                ->with('error', 'Gagal memuat unit: ' . $e->getMessage());
        }
    }

    public function show(string $slug)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $unit = $this->unitService->getDetailBySlug($slug);
            $ratingStats = $this->ratingService->getRatingStats($unit->id);
            $hasRated = $this->ratingService->hasUserRated($unit->id, $student->id);
            $categories = $this->ratingService->getActiveCategories();
            
            return view('student.units.show', compact('unit', 'ratingStats', 'hasRated', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('student.units.index')
                ->with('error', 'Unit tidak ditemukan');
        }
    }
}