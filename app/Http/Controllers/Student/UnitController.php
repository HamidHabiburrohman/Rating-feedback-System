<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\Student\RatingService;
use App\Services\Student\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected RatingService $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type', 'department', 'sort']);

        $units = app(UnitService::class)->getFilteredUnits($filters);
        $unitTypes = app(UnitService::class)->getActiveUnitTypes();
        $departments = app(UnitService::class)->getActiveDepartments();

        return view('student.units.index', compact('units', 'unitTypes', 'departments'));
    }

    public function show(Unit $unit)
    {
        $studentId = auth('student')->id();

        $hasRated = $studentId
            ? $this->ratingService->hasUserRated($unit->id, $studentId)
            : false;

        $unit->load(['type', 'department', 'facilities', 'photos' => function($q) {
            $q->orderBy('sort_order');
        }, 'ratings' => function($q) {
            $q->with('student')
                ->where('status', 'active')
                ->latest()
                ->limit(10);
        }]);

        return view('student.units.show', [
            'unit' => $unit,
            'hasRated' => $hasRated,
            'stats' => $this->ratingService->getRatingStats($unit->id)
        ]);
    }
}