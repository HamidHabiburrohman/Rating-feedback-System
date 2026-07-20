<?php

namespace App\Http\Controllers\Student\Rating;

use App\Http\Controllers\Controller;
use App\Services\Student\RatingService;
use App\Services\Student\QrValidationService;
use App\Models\Unit\Unit;
use App\Models\Feedback\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected RatingService $service;
    protected QrValidationService $validationService;

    public function __construct(
        RatingService $service,
        QrValidationService $validationService
    ) {
        $this->service = $service;
        $this->validationService = $validationService;
    }

    public function create(Unit $unit)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            if ($this->service->hasUserRated($unit->id, $student->id)) {
                return redirect()->route('student.ratings.history')
                    ->with('info', 'Anda sudah memberikan rating untuk unit ini');
            }
            
            $categories = $this->service->getActiveCategories();
            
            return view('student.ratings.create', compact('unit', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('student.units.index')
                ->with('error', 'Gagal memuat form rating: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);
        
        try {
            $rating = $this->service->submitRating($request->all(), $student->id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rating berhasil dikirim',
                    'tracking_code' => $rating->tracking_code,
                    'redirect' => route('student.ratings.show', $rating->tracking_code)
                ]);
            }
            
            return redirect()->route('student.ratings.show', $rating->tracking_code)
                ->with('success', 'Rating berhasil dikirim');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim rating: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal mengirim rating: ' . $e->getMessage());
        }
    }

    public function show(string $trackingCode)
    {
        try {
            $rating = $this->service->findByTrackingCode($trackingCode);
            $categories = $this->service->getActiveCategoriesWithScores($rating);
            
            return view('student.ratings.show', compact('rating', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('student.ratings.history')
                ->with('error', 'Rating tidak ditemukan');
        }
    }

    public function edit(string $trackingCode)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $rating = $this->service->findByTrackingCode($trackingCode);
            
            if (!$this->service->canEdit($rating)) {
                return redirect()->route('student.ratings.show', $trackingCode)
                    ->with('error', 'Rating ini tidak dapat diedit');
            }
            
            $categories = $this->service->getActiveCategoriesWithScores($rating);
            
            return view('student.ratings.edit', compact('rating', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('student.ratings.history')
                ->with('error', 'Rating tidak ditemukan');
        }
    }

    public function update(Request $request, string $trackingCode)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);
        
        try {
            $rating = $this->service->findByTrackingCode($trackingCode);
            
            if (!$this->service->canEdit($rating)) {
                throw new \Exception('Rating ini tidak dapat diedit');
            }
            
            $updated = $this->service->updateRating($rating, $request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rating berhasil diperbarui',
                    'redirect' => route('student.ratings.show', $trackingCode)
                ]);
            }
            
            return redirect()->route('student.ratings.show', $trackingCode)
                ->with('success', 'Rating berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui rating: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal memperbarui rating: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $filters = $request->only(['status', 'search', 'sort', 'order', 'per_page']);
            $ratings = $this->service->getUserRatings($student->id, $filters);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('student.ratings.partials.list', compact('ratings'))->render(),
                    'pagination' => view('student.ratings.partials.pagination', ['paginator' => $ratings])->render()
                ]);
            }
            
            return view('student.ratings.history', compact('ratings'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat riwayat rating: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('student.dashboard.index')
                ->with('error', 'Gagal memuat riwayat rating: ' . $e->getMessage());
        }
    }

    public function unitRatings(Request $request, Unit $unit)
    {
        try {
            $filters = $request->only(['sort', 'per_page']);
            $ratings = $this->service->getUnitRatings($unit->id, $filters);
            $stats = $this->service->getRatingStats($unit->id);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('student.ratings.partials.unit-list', compact('ratings'))->render(),
                    'pagination' => view('student.ratings.partials.pagination', ['paginator' => $ratings])->render(),
                    'stats' => $stats
                ]);
            }
            
            return view('student.ratings.unit', compact('unit', 'ratings', 'stats'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat rating unit: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('student.units.show', $unit->slug)
                ->with('error', 'Gagal memuat rating unit: ' . $e->getMessage());
        }
    }
}