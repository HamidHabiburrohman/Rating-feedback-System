<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\Student\RatingService;
use App\Services\Student\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    protected RatingService $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(Request $request)
    {
        try {
            Log::info('UnitController index called', [
                'url' => $request->fullUrl(),
                'ajax' => $request->ajax(),
                'wantsJson' => $request->wantsJson(),
                'filters' => $request->only(['search', 'type', 'department', 'sort'])
            ]);

            $filters = $request->only(['search', 'type', 'department', 'sort']);

            $units = app(UnitService::class)->getFilteredUnits($filters);

            Log::info('Units retrieved', [
                'total' => $units->total(),
                'per_page' => $units->perPage(),
                'current_page' => $units->currentPage()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                Log::info('Processing AJAX request');
                
                $gridHtml = view('student.units.partials.unit-grid', compact('units'))->render();
                Log::info('Grid HTML rendered, length: ' . strlen($gridHtml));
                
                $paginationHtml = view('layouts.student.partials.pagination', ['paginator' => $units])->render();
                Log::info('Pagination HTML rendered, length: ' . strlen($paginationHtml));
                
                return response()->json([
                    'grid' => $gridHtml,
                    'pagination' => $paginationHtml,
                ]);
            }

            $unitTypes = app(UnitService::class)->getActiveUnitTypes();
            $departments = app(UnitService::class)->getActiveDepartments();

            Log::info('Rendering full page', [
                'unitTypes_count' => count($unitTypes),
                'departments_count' => count($departments)
            ]);

            return view('student.units.index', compact('units', 'unitTypes', 'departments'));
            
        } catch (\Exception $e) {
            Log::error('UnitController index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
            
            throw $e;
        }
    }

    public function show(Unit $unit)
    {
        try {
            Log::info('========== START DEBUG UNIT SHOW ==========');
            Log::info('Unit ID: ' . $unit->id);
            Log::info('Unit Slug: ' . $unit->slug);
            Log::info('Unit Name: ' . $unit->name);
            
            $userRating = null;
            $canReport = false;
            $hasRated = false;

            if (auth('student')->check()) {
                $studentIdentifier = auth('student')->user()->student_identifier;
                Log::info('Student authenticated', ['student_identifier' => $studentIdentifier]);

                $userRating = $unit->ratings()
                    ->where('student_identifier', $studentIdentifier)
                    ->first();

                $hasRated = !is_null($userRating);

                if ($userRating) {
                    $reportService = app(\App\Services\Student\ReportService::class);
                    $canReport = $reportService->canReport($userRating);
                }
            }

            Log::info('Before loading relationships');
            
            $unit->load([
                'type', 
                'department', 
                'facilities', 
                'primaryPhoto',
                'photos' => function ($q) {
                    $q->orderBy('sort_order');
                }, 
                'ratings' => function ($q) {
                    $q->with('student')
                        ->where('status', 'active')
                        ->latest()
                        ->limit(10);
                }
            ]);

            Log::info('After loading relationships');
            Log::info('Primary Photo relationship loaded: ' . ($unit->relationLoaded('primaryPhoto') ? 'YES' : 'NO'));
            Log::info('Photos relationship loaded: ' . ($unit->relationLoaded('photos') ? 'YES' : 'NO'));
            
            $primaryPhoto = $unit->getRelation('primaryPhoto');
            Log::info('Primary Photo object:', [
                'exists' => $primaryPhoto ? 'YES' : 'NO',
                'is_null' => is_null($primaryPhoto),
                'type' => $primaryPhoto ? get_class($primaryPhoto) : 'null'
            ]);
            
            if ($primaryPhoto) {
                Log::info('Primary Photo details:', [
                    'id' => $primaryPhoto->id,
                    'unit_id' => $primaryPhoto->unit_id,
                    'is_primary' => $primaryPhoto->is_primary,
                    'thumbnail_url_exists' => isset($primaryPhoto->thumbnail_url),
                    'thumbnail_url_value' => $primaryPhoto->thumbnail_url ?? 'null',
                    'thumbnail_path_exists' => isset($primaryPhoto->thumbnail_path),
                    'thumbnail_path_value' => $primaryPhoto->thumbnail_path ?? 'null',
                    'original_path_exists' => isset($primaryPhoto->original_path),
                    'original_path_value' => $primaryPhoto->original_path ?? 'null',
                    'file_name' => $primaryPhoto->file_name ?? 'null'
                ]);
            } else {
                Log::warning('Primary Photo is NULL');
                $photos = $unit->getRelation('photos');
                Log::info('Total photos: ' . ($photos ? $photos->count() : 0));
                
                if ($photos && $photos->count() > 0) {
                    $firstPhoto = $photos->first();
                    Log::info('First photo details:', [
                        'id' => $firstPhoto->id,
                        'is_primary' => $firstPhoto->is_primary,
                        'thumbnail_url_value' => $firstPhoto->thumbnail_url ?? 'null',
                        'thumbnail_path_value' => $firstPhoto->thumbnail_path ?? 'null',
                        'original_path_value' => $firstPhoto->original_path ?? 'null'
                    ]);
                }
            }
            
            $thumbnailUrl = $unit->thumbnail_url;
            Log::info('Thumbnail URL from accessor: ' . ($thumbnailUrl ?? 'null'));
            
            $dbCheck = DB::table('unit_photos')
                ->where('unit_id', $unit->id)
                ->where('is_primary', true)
                ->first();
            
            Log::info('Database direct check for primary photo:', [
                'found' => $dbCheck ? 'YES' : 'NO',
                'photo_id' => $dbCheck->id ?? null,
                'thumbnail_path' => $dbCheck->thumbnail_path ?? null,
                'original_path' => $dbCheck->original_path ?? null
            ]);
            
            $allPhotosCount = DB::table('unit_photos')
                ->where('unit_id', $unit->id)
                ->count();
            
            Log::info('Total photos in database for this unit: ' . $allPhotosCount);
            
            $stats = $this->ratingService->getRatingStats($unit->id);
            
            Log::info('========== END DEBUG UNIT SHOW ==========');

            return view('student.units.show', [
                'unit' => $unit,
                'hasRated' => $hasRated,
                'userRating' => $userRating,
                'canReport' => $canReport,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('UnitController show error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}