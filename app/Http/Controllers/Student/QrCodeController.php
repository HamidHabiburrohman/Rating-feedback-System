<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\QrValidationService;
use App\Services\Student\RatingService;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected QrValidationService $validationService;
    protected RatingService $ratingService;

    public function __construct(
        QrValidationService $validationService,
        RatingService $ratingService
    ) {
        $this->validationService = $validationService;
        $this->ratingService = $ratingService;
    }

    public function scanForm()
    {
        return view('student.qr.scan');
    }

    public function scanResult(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $result = $this->validationService->validateQrCode($request->code);
        
        if (!$result['valid']) {
            return redirect()->route('student.qr.scan')
                ->with('error', $result['message']);
        }
        
        return view('student.qr.result', [
            'unit' => $result['unit'],
            'qr_code' => $result['qr_code'],
        ]);
    }

    public function validateQr(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        try {
            $result = $this->validationService->validateQrCode($request->code);
            
            return response()->json([
                'success' => true,
                'valid' => $result['valid'],
                'message' => $result['message'],
                'unit' => $result['unit'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memvalidasi QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus(int $unitId)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $status = $this->validationService->checkUnitStatus($unitId);
            $hasRated = $this->ratingService->hasUserRated($unitId, $student->id);
            
            return response()->json([
                'success' => true,
                'status' => $status,
                'has_rated' => $hasRated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status: ' . $e->getMessage()
            ], 500);
        }
    }
}