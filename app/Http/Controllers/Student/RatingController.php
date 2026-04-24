<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Rating\StoreRatingRequest;
use App\Http\Requests\Student\Rating\UpdateRatingRequest;
use App\Http\Requests\Student\Rating\RatingFilterRequest;
use App\Services\Student\RatingService;
use App\Services\Student\ReportService;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    protected RatingService $service;
    protected ReportService $reportService;

    public function __construct(RatingService $service, ReportService $reportService)
    {
        $this->service = $service;
        $this->reportService = $reportService;
    }

    public function create(Unit $unit)
    {
        $studentIdentifier = auth('student')->user()->student_identifier;

        if ($this->service->hasUserRated($unit->id, $studentIdentifier)) {
            return redirect()->route('student.units.show', $unit->slug)
                ->with('error', 'Anda sudah memberikan rating untuk unit ini');
        }

        return view('student.ratings.create', [
            'unit' => $unit,
            'categories' => $this->service->getActiveCategories()
        ]);
    }

    public function store(StoreRatingRequest $request)
    {
        try {
            $studentIdentifier = auth('student')->user()->student_identifier;

            $rating = $this->service->submitRating(
                $request->validated(),
                $studentIdentifier
            );

            return redirect()->route('student.units.show', $rating->unit->slug)
                ->with('success', 'Rating berhasil dikirim');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal mengirim rating: ' . $e->getMessage());
        }
    }

    public function show($trackingCode)
    {
        try {
            $rating = $this->service->findByTrackingCode($trackingCode);
            $studentIdentifier = auth('student')->user()->student_identifier;

            if ($rating->student_identifier !== $studentIdentifier) {
                abort(403);
            }

            $canEdit = $this->service->canEdit($rating);
            $canReport = $this->reportService->canReport($rating);

            return view('student.ratings.show', [
                'rating' => $rating,
                'stats' => $this->service->getRatingStats($rating->unit_id),
                'canEdit' => $canEdit,
                'canReport' => $canReport
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.ratings.history')
                ->with('error', 'Rating tidak ditemukan');
        }
    }

    public function edit($trackingCode)
    {
        try {
            $rating = $this->service->findByTrackingCode($trackingCode);
            $studentIdentifier = auth('student')->user()->student_identifier;

            if ($rating->student_identifier !== $studentIdentifier) {
                abort(403);
            }

            if (!$this->service->canEdit($rating)) {
                return redirect()->route('student.ratings.show', $trackingCode)
                    ->with('error', 'Tidak dapat mengedit rating ini');
            }

            $categories = $this->service->getActiveCategoriesWithScores($rating);

            return view('student.ratings.edit', [
                'rating' => $rating,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.ratings.history')
                ->with('error', 'Rating tidak ditemukan');
        }
    }

    public function update(UpdateRatingRequest $request, $trackingCode)
    {
        try {
            $rating = $this->service->findByTrackingCode($trackingCode);
            $studentIdentifier = auth('student')->user()->student_identifier;

            if ($rating->student_identifier !== $studentIdentifier) {
                abort(403);
            }

            $this->service->updateRating($rating, $request->validated());

            return redirect()->route('student.units.show', $rating->unit->slug)
                ->with('success', 'Rating berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui rating: ' . $e->getMessage());
        }
    }

    public function history(RatingFilterRequest $request)
    {
        $studentIdentifier = auth('student')->user()->student_identifier;

        return view('student.ratings.history', [
            'ratings' => $this->service->getUserRatings(
                $studentIdentifier,
                $request->validated()
            )
        ]);
    }

    public function unitRatings(RatingFilterRequest $request, Unit $unit)
    {
        $studentIdentifier = auth('student')->user()->student_identifier;

        return view('student.ratings.unit', [
            'unit' => $unit,
            'ratings' => $this->service->getUnitRatings($unit->id, $request->validated()),
            'stats' => $this->service->getRatingStats($unit->id),
            'canRate' => !$this->service->hasUserRated($unit->id, $studentIdentifier)
        ]);
    }
}