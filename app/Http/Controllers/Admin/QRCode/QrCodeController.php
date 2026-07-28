<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin\QRCode;

use App\Http\Controllers\Controller;
use App\Models\Unit\QrCode;
use App\Models\Unit\Unit;
use App\Services\Admin\QRCode\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QrCodeController extends Controller
{
    public function __construct(
        protected readonly QrCodeService $service
    ) {}

    public function globalIndex(Request $request): View|JsonResponse
    {
        $this->authorize('viewAny', QrCode::class);
        try {
            $filters = $request->only(['search', 'status', 'unit_id', 'sort', 'order', 'per_page']);
            $qrCodes = $this->service->getAllQrCodes($filters);
            $stats = $this->service->getQrCodeStats();
            $units = Unit::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.qr-codes.partials.rows', ['qrCodes' => $qrCodes])->render(),
                    'pagination' => view('admin.qr-codes.partials.pagination', ['paginator' => $qrCodes])->render(),
                ]);
            }
            
            return view('admin.qr-codes.index', [
                'qrCodes' => $qrCodes,
                'stats' => $stats,
                'units' => $units,
                'filters' => $filters,
            ]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data QR Code: ' . $e->getMessage(),
                ], 500);
            }
            $emptyQrCodes = new LengthAwarePaginator([], 0, 10);
            return view('admin.qr-codes.index', [
                'qrCodes' => $emptyQrCodes,
                'stats' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'expired' => 0],
                'units' => collect(),
                'filters' => [],
            ])->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function generateGlobal(Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('create', QrCode::class);
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);
        
        try {
            $qrCode = $this->service->generate(
                (int) $validated['unit_id'],
                (int) auth('admin')->id()
            );
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'QR Code berhasil digenerate.',
                    'data' => $qrCode,
                ]);
            }
            
            return redirect()->route('admin.qr-codes.index')
                ->with('success', 'QR Code berhasil digenerate.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal generate QR Code: ' . $e->getMessage(),
                ], 500);
            }
            return back()->withInput()->with('error', 'Gagal generate QR Code: ' . $e->getMessage());
        }
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getQrCodeStats(),
        ]);
    }

    public function index(Unit $unit): View
    {
        $this->authorize('view', $unit);
        $qrCode = $this->service->getByUnit($unit->id);
        return view('admin.units.qr-codes.index', [
            'unit' => $unit,
            'qrCode' => $qrCode,
        ]);
    }

    public function previewData(QrCode $qrCode): \Illuminate\Http\JsonResponse
    {
        $this->authorize('view', $qrCode->unit);
        $qrCode->load('unit.unitDepartment', 'generatedByAdmin');
        $qrCode->loadCount('unitVisits');
        $isExpired = $qrCode->expires_at && $qrCode->expires_at->isPast();
        $status = $qrCode->is_active && !$isExpired ? 'active' : ($isExpired ? 'expired' : 'inactive');
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $qrCode->id,
                'code' => $qrCode->code,
                'image_url' => $qrCode->qr_image_path ? asset('storage/' . $qrCode->qr_image_path) : null,
                'unit_name' => $qrCode->unit->name ?? 'Unknown',
                'unit_code' => $qrCode->unit->code ?? 'N/A',
                'department' => $qrCode->unit->unitDepartment->name ?? 'N/A',
                'status' => $status,
                'generated_by' => $qrCode->generatedByAdmin->nama ?? ($qrCode->generatedByAdmin->name ?? 'System'),
                'generated_at' => $qrCode->created_at->format('M d, Y H:i'),
                'last_generated' => $qrCode->updated_at->format('M d, Y H:i'),
                'expiration_date' => $qrCode->expires_at ? $qrCode->expires_at->format('M d, Y') : 'Never',
                'total_scans' => $qrCode->unitVisits_count ?? 0,
                'download_url' => route('admin.qr-codes.download', $qrCode->id),
            ]
        ]);
    }

    public function generate(Unit $unit): RedirectResponse
    {
        $this->authorize('update', $unit);
        $this->service->generate($unit->id, (int) auth('admin')->id());
        return back()->with('success', 'QR Code berhasil digenerate.');
    }

    public function regenerate(QrCode $qrCode): RedirectResponse
    {
        $this->authorize('update', $qrCode->unit);
        $this->service->regenerate($qrCode->id, (int) auth('admin')->id());
        return back()->with('success', 'QR Code berhasil digenerate ulang.');
    }

    public function activate(QrCode $qrCode): RedirectResponse
    {
        $this->authorize('update', $qrCode->unit);
        $this->service->activate($qrCode->id);
        return back()->with('success', 'QR Code berhasil diaktifkan.');
    }

    public function deactivate(QrCode $qrCode): RedirectResponse
    {
        $this->authorize('update', $qrCode->unit);
        $this->service->deactivate($qrCode->id);
        return back()->with('success', 'QR Code berhasil dinonaktifkan.');
    }

    public function preview(Request $request, QrCode $qrCode): View|JsonResponse
    {
        $this->authorize('view', $qrCode->unit);
        if ($request->ajax() || $request->wantsJson()) {
            $qrCode->load('unit.unitDepartment', 'generatedByAdmin');
            $qrCode->loadCount('unitVisits');
            $isExpired = $qrCode->expires_at && $qrCode->expires_at->isPast();
            $status = $qrCode->is_active && !$isExpired ? 'active' : ($isExpired ? 'expired' : 'inactive');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $qrCode->id,
                    'code' => $qrCode->code,
                    'image_url' => $qrCode->qr_image_path ? asset('storage/' . $qrCode->qr_image_path) : null,
                    'unit_name' => $qrCode->unit->name ?? 'Unknown',
                    'unit_code' => $qrCode->unit->code ?? 'N/A',
                    'department' => $qrCode->unit->unitDepartment->name ?? 'N/A',
                    'status' => $status,
                    'generated_by' => $qrCode->generatedByAdmin->nama ?? ($qrCode->generatedByAdmin->name ?? 'System'),
                    'generated_at' => $qrCode->created_at->format('M d, Y H:i'),
                    'last_generated' => $qrCode->updated_at->format('M d, Y H:i'),
                    'expiration_date' => $qrCode->expires_at ? $qrCode->expires_at->format('M d, Y') : 'Never',
                    'total_scans' => $qrCode->unitVisits_count ?? 0,
                    'download_url' => route('qr-codes.download', $qrCode->id),
                    'scan_url' => url("/student/qr/scan?code={$qrCode->code}"),
                ]
            ]);
        }
        
        $url = $this->service->preview($qrCode->id);
        return view('admin.qr-codes.preview', [
            'qrCode' => $qrCode,
            'url' => $url,
        ]);
    }

    public function download(QrCode $qrCode): BinaryFileResponse
    {
        $this->authorize('view', $qrCode->unit);
        return $this->service->download($qrCode->id);
    }

    public function destroy(QrCode $qrCode): RedirectResponse
    {
        $this->authorize('delete', $qrCode->unit);
        $qrCode->delete();
        return back()->with('success', 'QR Code berhasil dihapus.');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = min((int) $request->get('limit', 5), 10);
        
        $unitsQuery = Unit::where('is_active', true)
            ->whereDoesntHave('qrCodes');
            
        $allHaveQr = (clone $unitsQuery)->count() === 0;

        $units = $unitsQuery
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->with(['unitType', 'unitDepartment'])
            ->limit($limit)
            ->get(['id', 'name', 'code', 'unit_type_id', 'unit_department_id']);

        return response()->json([
            'success' => true,
            'units' => $units->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'code' => $unit->code ?? 'N/A',
                    'type' => $unit->unitType ? $unit->unitType->name : 'General',
                    'department' => $unit->unitDepartment ? $unit->unitDepartment->name : 'No Department'
                ];
            }),
            'all_have_qr' => $allHaveQr
        ]);
    }

    public function searchUnits(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = min((int) $request->get('limit', 10), 20);
        
        $unitsQuery = Unit::where('is_active', true)
            ->whereDoesntHave('qrCodes');
            
        $allHaveQr = (clone $unitsQuery)->count() === 0;

        $units = $unitsQuery
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->with(['unitType', 'unitDepartment'])
            ->limit($limit)
            ->get(['id', 'name', 'code', 'unit_type_id', 'unit_department_id']);

        return response()->json([
            'success' => true,
            'units' => $units->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'code' => $unit->code ?? 'N/A',
                    'type' => $unit->unitType ? $unit->unitType->name : 'General',
                    'department' => $unit->unitDepartment ? $unit->unitDepartment->name : 'No Department'
                ];
            }),
            'all_have_qr' => $allHaveQr
        ]);
    }
}