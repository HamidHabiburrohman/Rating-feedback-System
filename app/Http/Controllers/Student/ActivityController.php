<?php
// app/Http/Controllers/Student/ActivityController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Report;
use App\Models\UnitVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    /**
     * Display recent activities (ratings & reports) for the authenticated student
     */
    public function index(Request $request)
    {
        $studentIdentifier = auth('student')->user()->student_identifier;
        $filter = $request->input('filter', 'all'); // all, ratings, reports
        
        // Get ratings
        $ratings = collect();
        if ($filter === 'all' || $filter === 'ratings') {
            $ratings = Rating::with(['unit'])
                ->where('student_identifier', $studentIdentifier)
                ->where('status', '!=', 'archived')
                ->get()
                ->map(function ($rating) {
                    return (object)[
                        'type' => 'rating',
                        'id' => $rating->id,
                        'tracking_code' => $rating->tracking_code,
                        'unit_id' => $rating->unit_id,
                        'unit_name' => $rating->unit?->name,
                        'overall_score' => $rating->overall_score,
                        'comment' => $rating->comment,
                        'status' => $rating->status,
                        'created_at' => $rating->created_at,
                        'created_at_human' => $rating->created_at->diffForHumans(),
                    ];
                });
        }
        
        // Get reports
        $reports = collect();
        if ($filter === 'all' || $filter === 'reports') {
            $reports = Report::with(['unit', 'rating.unit'])
                ->where('student_identifier', $studentIdentifier)
                ->get()
                ->map(function ($report) {
                    return (object)[
                        'type' => 'report',
                        'id' => $report->id,
                        'tracking_code' => $report->tracking_code,
                        'unit_id' => $report->unit_id,
                        'unit_name' => $report->unit?->name ?? $report->rating?->unit?->name,
                        'title' => $report->title,
                        'description' => $report->description,
                        'status' => $report->status,
                        'priority' => $report->priority,
                        'admin_response' => $report->admin_response,
                        'created_at' => $report->created_at,
                        'created_at_human' => $report->created_at->diffForHumans(),
                    ];
                });
        }
        
        // Merge and sort activities by created_at (newest first)
        $activities = $ratings->concat($reports)
            ->sortByDesc('created_at')
            ->values();
        
        // Apply pagination manually (10 per page)
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $currentItems = $activities->slice(($currentPage - 1) * $perPage, $perPage);
        
        $paginatedActivities = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $activities->count(),
            $perPage,
            $currentPage,
            ['path' => route('student.activities.index')]
        );

        $totalActivities = $activities->count();
        
        return view('student.activities.index', [
            'activities' => $paginatedActivities,
            'currentFilter' => $filter,
            'totalRatings' => $ratings->count(),
            'totalReports' => $reports->count(),
            'totalActivities' => $totalActivities,
        ]);
    }
}