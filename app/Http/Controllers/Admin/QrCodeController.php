<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QrCode\GenerateQrCodeRequest;
use App\Http\Requests\Admin\QrCode\RegenerateQrCodeRequest;
use App\Services\Admin\QrCodeService;
use App\Services\Admin\UnitService;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected QrCodeService $qrCodeService;
    protected UnitService $unitService;

    public function __construct(QrCodeService $qrCodeService, UnitService $unitService)
    {
        $this->qrCodeService = $qrCodeService;
        $this->unitService = $unitService;
    }

    public function index()
    {
        $units = $this->unitService->getActive();
        return view('admin.qr_codes.index', compact('units'));
    }

    public function show($unitId)
    {
        $unit = $this->unitService->findById($unitId);
        if (!$unit) {
            return redirect()->route('admin.qr-codes.index')->with('error', 'Unit not found');
        }
        $qrCodes = $this->qrCodeService->getByUnit($unitId);
        return view('admin.qr_codes.show', compact('unit', 'qrCodes'));
    }

    public function generate(GenerateQrCodeRequest $request)
    {
        $unitId = $request->unit_id;
        $unit = $this->unitService->findById($unitId);
        if (!$unit) {
            return response()->json(['success' => false, 'message' => 'Unit not found'], 404);
        }

        $qrCode = $this->qrCodeService->generateForUnit($unit, auth('admin')->id());
        return response()->json(['success' => true, 'data' => $qrCode]);
    }

    public function regenerate(RegenerateQrCodeRequest $request, $id)
    {
        $qrCode = $this->qrCodeService->regenerate($id, auth('admin')->id());
        if (!$qrCode) {
            return response()->json(['success' => false, 'message' => 'QR Code not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $qrCode]);
    }

    public function toggleActive($id)
    {
        $updated = $this->qrCodeService->toggleActive($id);
        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'QR Code not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'QR Code status updated']);
    }

    public function destroy($id)
    {
        $deleted = $this->qrCodeService->delete($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'QR Code not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'QR Code deleted successfully']);
    }
}