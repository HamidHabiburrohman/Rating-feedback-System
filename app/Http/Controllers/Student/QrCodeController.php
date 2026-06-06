<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\QrCode\QrValidationRequest;
use App\Services\Student\QrValidationService;
use App\Services\Student\RatingService;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected QrValidationService $qrValidationService;
    protected RatingService $ratingService;

    public function __construct(QrValidationService $qrValidationService, RatingService $ratingService)
    {
        $this->qrValidationService = $qrValidationService;
        $this->ratingService = $ratingService;
    }

    public function scanForm()
    {
        $student = auth('student')->user();
        $this->qrValidationService->setStudent($student);
        return view('student.qr.scan');
    }

    public function scanResult(Request $request)
    {
        $qrCode = $request->get('qr_code');
        return view('student.qr.result', compact('qrCode'));
    }

    public function validateQr(QrValidationRequest $request)
    {
        $student = auth('student')->user();
        $this->qrValidationService->setStudent($student);
        $this->ratingService->setStudent($student);

        $data = $request->validated();

        $qrResult = $this->qrValidationService->validateQr($data['qr_code']);

        if (!$qrResult['valid']) {
            return response()->json(['success' => false, 'message' => $qrResult['message']], 400);
        }

        $qrCode = $qrResult['qr_code'];
        $unit = $qrResult['unit'];

        $gpsResult = $this->qrValidationService->validateGps(
            $data['latitude'],
            $data['longitude'],
            $qrCode
        );

        $visit = $this->qrValidationService->createVisit(
            $unit->id,
            $qrCode->id,
            $data['latitude'],
            $data['longitude'],
            $gpsResult['valid']
        );

        $hasActiveRating = $this->qrValidationService->hasActiveRating($unit->id);

        return response()->json([
            'success' => true,
            'data' => [
                'unit' => $unit,
                'qr_code' => $qrCode,
                'gps_validation' => $gpsResult,
                'visit_id' => $visit->id,
                'has_active_rating' => $hasActiveRating,
                'can_rate' => !$hasActiveRating && $gpsResult['valid'],
            ]
        ]);
    }

    public function checkStatus($unitId)
    {
        $student = auth('student')->user();
        $this->qrValidationService->setStudent($student);

        $hasActiveRating = $this->qrValidationService->hasActiveRating($unitId);

        return response()->json([
            'success' => true,
            'has_active_rating' => $hasActiveRating,
            'can_rate' => !$hasActiveRating,
        ]);
    }
}