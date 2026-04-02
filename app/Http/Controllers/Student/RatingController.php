<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Rating\StoreRatingRequest;
use App\Http\Requests\Student\Rating\UpdateRatingRequest;
use App\Http\Requests\Student\Rating\RatingFilterRequest;
use App\Services\Student\RatingService;
use App\Models\Unit;

class RatingController extends Controller
{
    protected RatingService $service;

    public function __construct(RatingService $service)
    {
        $this->service = $service;
    }

    public function create(Unit $unit)
    {
        if ($this->service->hasUserRated($unit->id, auth('student')->id())) {
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
            $rating = $this->service->submitRating(
                $request->validated(),
                auth('student')->id()
            );

            return redirect()->route('student.ratings.show', $rating->tracking_code)
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

            if ($rating->student_id !== auth('student')->id()) {
                abort(403);
            }

            return view('student.ratings.show', [
                'rating' => $rating,
                'stats' => $this->service->getRatingStats($rating->unit_id)
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

            if ($rating->student_id !== auth('student')->id()) {
                abort(403);
            }

            if (!$this->service->canEdit($rating)) {
                return redirect()->route('student.ratings.show', $trackingCode)
                    ->with('error', 'Belum bisa mengedit rating');
            }

            return view('student.ratings.edit', [
                'rating' => $rating,
                'categories' => $this->service->getActiveCategoriesWithScores($rating)
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

            if ($rating->student_id !== auth('student')->id()) {
                abort(403);
            }

            $this->service->updateRating($rating, $request->validated());

            return redirect()->route('student.ratings.show', $trackingCode)
                ->with('success', 'Rating berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui rating: ' . $e->getMessage());
        }
    }

    public function history(RatingFilterRequest $request)
    {
        return view('student.ratings.history', [
            'ratings' => $this->service->getUserRatings(
                auth('student')->id(),
                $request->validated()
            )
        ]);
    }

    public function unitRatings(RatingFilterRequest $request, Unit $unit)
    {
        return view('student.ratings.unit', [
            'unit' => $unit,
            'ratings' => $this->service->getUnitRatings($unit->id, $request->validated()),
            'stats' => $this->service->getRatingStats($unit->id),
            'canRate' => !$this->service->hasUserRated($unit->id, auth('student')->id())
        ]);
    }
}