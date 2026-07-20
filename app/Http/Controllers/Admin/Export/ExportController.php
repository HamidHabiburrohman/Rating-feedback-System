<?php

namespace App\Http\Controllers\Admin\Export;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Export\ExportRequest;
use App\Services\Export\ExportManager;
use App\Models\Report\Report;
use App\Models\Feedback\Rating;
use App\Models\Unit\Unit;
use App\Models\Unit\UnitType;
use App\Models\System\Export;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected ExportManager $manager;

    public function __construct(ExportManager $manager)
    {
        $this->manager = $manager;
    }

    public function index()
    {
        $this->authorize('viewAny', Export::class);

        $stats = [
            'total_reports' => Report::count(),
            'total_ratings' => Rating::count(),
            'total_units' => Unit::count(),
            'total_unit_types' => UnitType::count()
    ];

        try {
            $recentExports = Export::with('admin')
                ->latest()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $recentExports = collect([]);
        }

        return view('admin.exports.index', compact('stats', 'recentExports'));
    }

    public function exportReports(ExportRequest $request)
    {
        $this->authorize('export', Report::class);
        try {
            $filters = $request->validated();
            $query = Report::query();

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            $data = $query->with(['student', 'category', 'unit'])->get();
            $format = $filters['format'] ?? 'excel';
            $options = [
                'filename' => 'reports_' . now()->format('Y-m-d_His'),
                'title' => 'Laporan Export'
            ];

            return $this->manager->exportReport($data, $options, $format);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengekspor laporan: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.exports.index')->with('error', 'Gagal mengekspor laporan: ' . $e->getMessage());
        }
    }

    public function exportRatings(ExportRequest $request)
    {
        $this->authorize('export', Rating::class);
        try {
            $filters = $request->validated();
            $query = Rating::query();

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            $data = $query->with(['student', 'unit', 'category'])->get();
            $format = $filters['format'] ?? 'excel';
            $options = [
                'filename' => 'ratings_' . now()->format('Y-m-d_His'),
                'title' => 'Rating Export'
            ];

            return $this->manager->exportRating($data, $options, $format);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengekspor rating: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.exports.index')->with('error', 'Gagal mengekspor rating: ' . $e->getMessage());
        }
    }

    public function exportUnits(ExportRequest $request)
    {
        $this->authorize('export', Unit::class);
        try {
            $filters = $request->validated();
            $query = Unit::query();

            if (!empty($filters['status'])) {
                $query->where('is_active', $filters['status'] === 'active');
            }
            if (!empty($filters['type_id'])) {
                $query->where('unit_type_id', $filters['type_id']);
            }

            $data = $query->with(['type', 'department', 'facilities'])->get();
            $format = $filters['format'] ?? 'excel';

            $rows = $data->map(function ($unit) {
                return [
                    'ID' => $unit->id,
                    'Nama' => $unit->name,
                    'Tipe' => $unit->type->name ?? '-',
                    'Departemen' => $unit->department->name ?? '-',
                    'Status' => $unit->is_active ? 'Aktif' : 'Nonaktif',
                    'Lokasi' => $unit->location ?? '-',
                    'Fasilitas' => $unit->facilities->pluck('name')->implode(', '),
                    'Dibuat' => $unit->created_at?->format('Y-m-d H:i:s')
    ];
            })->toArray();

            $headers = array_keys($rows[0] ?? []);
            $filename = 'units_' . now()->format('Y-m-d_His');
            $title = 'Unit Export';

            return match (strtolower($format)) {
                'pdf' => $this->manager->getPdfService()->export($rows, $headers, $title, $filename),
                'csv' => $this->manager->getCsvService()->export($rows, $headers, $title, $filename),
                default => $this->manager->getExcelService()->export($rows, $headers, $title, $filename),
            };
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengekspor unit: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.exports.index')->with('error', 'Gagal mengekspor unit: ' . $e->getMessage());
        }
    }

    public function exportUnitTypes(ExportRequest $request)
    {
        $this->authorize('export', UnitType::class);
        try {
            $data = UnitType::withCount('units')->get();
            $filters = $request->validated();
            $format = $filters['format'] ?? 'excel';

            $rows = $data->map(function ($type) {
                return [
                    'ID' => $type->id,
                    'Nama' => $type->name,
                    'Deskripsi' => $type->description ?? '-',
                    'Icon' => $type->icon ?? '-',
                    'Jumlah Unit' => $type->units_count,
                    'Status' => $type->is_active ? 'Aktif' : 'Nonaktif',
                    'Urutan' => $type->sort_order ?? 0,
                    'Dibuat' => $type->created_at?->format('Y-m-d H:i:s')
    ];
            })->toArray();

            $headers = array_keys($rows[0] ?? []);
            $filename = 'unit_types_' . now()->format('Y-m-d_His');
            $title = 'Unit Type Export';

            return match (strtolower($format)) {
                'pdf' => $this->manager->getPdfService()->export($rows, $headers, $title, $filename),
                'csv' => $this->manager->getCsvService()->export($rows, $headers, $title, $filename),
                default => $this->manager->getExcelService()->export($rows, $headers, $title, $filename),
            };
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengekspor tipe unit: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.exports.index')->with('error', 'Gagal mengekspor tipe unit: ' . $e->getMessage());
        }
    }

    public function downloadExport(Request $request, int $id)
    {
        try {
            $export = Export::findOrFail($id);
            $this->authorize('download', $export);

            if ($export->isExpired()) {
                throw new \Exception('File export sudah kedaluwarsa');
            }

            if (!$export->isCompleted()) {
                throw new \Exception('File export belum siap');
            }

            $export->increment('download_count');
            $export->update(['last_downloaded_at' => now()]);

            return $this->manager->download($export->file_path, $export->file_name);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengunduh file: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.exports.index')->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }
}