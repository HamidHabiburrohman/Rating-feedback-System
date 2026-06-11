<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\QrCodeService;
use App\Models\Unit\Unit;
use App\Models\Unit\QrCode;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected QrCodeService $service;

    public function __construct(QrCodeService $service)
    {
        $this->service = $service;
    }

    public function index(int $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('view', $unit);
        
        try {
            $qrCodes = $this->service->getByUnit($unitId);
            return view('admin.units.qr-codes.index', compact('unit', 'qrCodes'));
        } catch (\Exception $e) {
            return redirect()->route('admin.units.show', $unitId)->with('error', 'Gagal memuat QR Code');
        }
    }

    public function generate(Request $request, int $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('update', $unit);
        
        try {
            $qrCode = $this->service->generate($unitId, auth('admin')->id());
            
            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil digenerate',
                'data' => $qrCode
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal generate: ' . $e->getMessage()], 500);
        }
    }

    public function regenerate(Request $request, int $id)
    {
        $qrCode = QrCode::findOrFail($id);
        $this->authorize('update', $qrCode->unit);
        
        try {
            $newQrCode = $this->service->regenerate($id, auth('admin')->id());
            
            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil digenerate ulang',
                'data' => $newQrCode
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal regenerate: ' . $e->getMessage()], 500);
        }
    }

    public function toggleActive(Request $request, int $id)
    {
        $qrCode = QrCode::findOrFail($id);
        $this->authorize('update', $qrCode->unit);
        
        try {
            $this->service->toggleActive($id);
            return response()->json(['success' => true, 'message' => 'Status QR Code berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, int $id)
    {
        $qrCode = QrCode::findOrFail($id);
        $this->authorize('delete', $qrCode->unit);
        
        try {
            $this->service->delete($id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'QR Code berhasil dihapus']);
            }
            
            return back()->with('success', 'QR Code berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}