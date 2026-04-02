<?php

namespace App\Services\Export;

use App\Services\Export\Contracts\ExportInterface;
use App\Models\Report;
use App\Models\Rating;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\Export;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ExportDataService
{
    public function __construct(
        private ExportInterface $exportManager
    ) {}

    public function exportReports(array $filters): Response
    {
        $reports = $this->getFilteredReports($filters);

        $options = [
            'title' => 'Laporan Pengaduan',
            'filename' => 'laporan_pengaduan_' . date('Y_m_d_His'),
        ];

        return $this->exportManager->exportReport($reports, $options, $filters['format'] ?? 'excel');
    }

    /**
     * Export print view untuk Reports
     */
    public function exportPrintView(array $filters)
    {
        $reports = $this->getFilteredReports($filters);
        
        // Data real dari database
        $headers = $this->getReportHeaders();
        $rows = $this->transformReportsForPrint($reports);
        $summary = $this->getReportSummary($reports);

        return view('exports.print', [
            'title' => 'Laporan Pengaduan ' . date('F Y'),
            'summary' => $summary, 
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    /**
     * Export print view untuk Ratings
     */
    public function exportRatingsPrintView(array $filters)
    {
        $ratings = $this->getFilteredRatings($filters);
        
        $headers = ['ID', 'Unit', 'Rating', 'Komentar', 'Status', 'Tanggal'];
        $rows = $ratings->map(fn($r) => [
            $r->id,
            $r->unit->nama_unit ?? '-',
            $r->nilai . '/5',
            $r->komentar ?? '-',
            $r->status_label,
            $r->created_at->format('d/m/Y'),
        ])->toArray();
        
        $summary = [
            'Total Rating' => $ratings->count(),
            'Avg Rating' => $ratings->avg('nilai') ? number_format($ratings->avg('nilai'), 1) . '/5' : '0/5',
            'Diterima' => $ratings->where('status', 'diterima')->count(),
            'Ditolak' => $ratings->where('status', 'ditolak')->count(),
        ];

        return view('exports.print', [
            'title' => 'Laporan Rating Unit ' . date('F Y'),
            'summary' => $summary,
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    /**
     * Export print view untuk Units
     */
    public function exportUnitsPrintView(array $filters)
    {
        $units = $this->getFilteredUnits($filters);
        
        $headers = $this->getUnitHeaders();
        $rows = $this->transformUnits($units);
        
        $summary = [
            'Total Unit' => $units->count(),
            'Aktif' => $units->where('status', 'aktif')->count(),
            'Nonaktif' => $units->where('status', 'nonaktif')->count(),
            'Total Tipe' => $units->pluck('type_id')->unique()->count(),
        ];

        return view('exports.print', [
            'title' => 'Data Unit ' . date('F Y'),
            'summary' => $summary,
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    public function exportRatings(array $filters): Response
    {
        $ratings = $this->getFilteredRatings($filters);

        $options = [
            'title' => 'Rating dan Ulasan',
            'filename' => 'rating_ulasan_' . date('Y_m_d_His'),
        ];

        return $this->exportManager->exportRating($ratings, $options, $filters['format'] ?? 'excel');
    }

    public function exportUnits(array $filters): Response
    {
        $units = $this->getFilteredUnits($filters);
        $rows = $this->transformUnits($units);
        $headers = $this->getUnitHeaders();

        return $this->exportManager->getExcelService()->export($rows, $headers, 'Data Unit', 'data_unit_' . date('Y_m_d_His'));
    }

    public function exportUnitTypes(array $filters): Response
    {
        $types = $this->getFilteredUnitTypes($filters);
        $rows = $this->transformUnitTypes($types);
        $headers = $this->getUnitTypeHeaders();

        return $this->exportManager->getExcelService()->export($rows, $headers, 'Tipe Unit', 'tipe_unit_' . date('Y_m_d_His'));
    }

    public function downloadExport($id): Response
    {
        $export = Export::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'completed')
            ->firstOrFail();

        if (!Storage::exists($export->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::download($export->file_path, $export->filename);
    }

    // ==================== PRIVATE METHODS ====================

    private function getReportHeaders(): array
    {
        return [
            'No',
            'Judul',
            'Tipe',
            'Prioritas',
            'Status',
            'Unit',
            'Tanggal'
        ];
    }

    private function transformReportsForPrint(Collection $reports): array
    {
        return $reports->map(function ($report, $index) {
            return [
                $index + 1, // No urut
                $report->judul,
                $report->tipe_label,
                $report->prioritas,
                $report->status_label,
                $report->unit->nama_unit ?? '-',
                $report->created_at->format('d/m/Y'),
            ];
        })->toArray();
    }

    private function getReportSummary(Collection $reports): array
    {
        return [
            'Total Laporan' => $reports->count(),
            'Baru' => $reports->where('status', 'baru')->count(),
            'Diproses' => $reports->where('status', 'diproses')->count(),
            'Selesai' => $reports->where('status', 'selesai')->count(),
        ];
    }

    private function getFilteredReports(array $filters): Collection
    {
        $query = Report::with(['unit', 'admin']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', "%{$filters['search']}%")
                    ->orWhere('deskripsi', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', explode(',', $filters['status']));
        }

        if (!empty($filters['tipe'])) {
            $query->whereIn('tipe', explode(',', $filters['tipe']));
        }

        if (!empty($filters['prioritas'])) {
            $query->whereIn('prioritas', explode(',', $filters['prioritas']));
        }

        if (!empty($filters['unit'])) {
            $query->whereIn('unit_id', explode(',', $filters['unit']));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->get();
    }

    private function getFilteredRatings(array $filters): Collection
    {
        $query = Rating::with(['unit']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('komentar', 'like', "%{$filters['search']}%")
                    ->orWhere('tracking_code', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', explode(',', $filters['status']));
        }

        if (!empty($filters['unit'])) {
            $query->whereIn('unit_id', explode(',', $filters['unit']));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->get();
    }

    private function getFilteredUnits(array $filters): Collection
    {
        $query = Unit::with(['type']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_unit', 'like', "%{$filters['search']}%")
                    ->orWhere('kode_unit', 'like', "%{$filters['search']}%")
                    ->orWhere('lokasi', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', explode(',', $filters['status']));
        }

        if (!empty($filters['type'])) {
            $query->whereIn('type_id', explode(',', $filters['type']));
        }

        return $query->orderBy('nama_unit')->get();
    }

    private function getFilteredUnitTypes(array $filters): Collection
    {
        $query = UnitType::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] == 'active');
        }

        return $query->orderBy('sort_order')->get();
    }

    private function transformUnits(Collection $units): array
    {
        return $units->map(function ($unit) {
            return [
                $unit->id,
                $unit->kode_unit,
                $unit->nama_unit,
                $unit->type->name ?? '-',
                $unit->lokasi,
                $unit->status,
                $unit->jam_buka ? $unit->jam_buka->format('H:i') : '-',
                $unit->jam_tutup ? $unit->jam_tutup->format('H:i') : '-',
                $unit->kontak_email,
                $unit->kontak_telepon,
                $unit->kapasitas,
                $unit->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();
    }

    private function transformUnitTypes(Collection $types): array
    {
        return $types->map(function ($type) {
            return [
                $type->id,
                $type->name,
                $type->description ?? '-',
                $type->is_active ? 'Ya' : 'Tidak',
                $type->sort_order,
                $type->created_at->format('d/m/Y H:i'),
                $type->updated_at->format('d/m/Y H:i'),
            ];
        })->toArray();
    }

    private function getUnitHeaders(): array
    {
        return [
            'ID',
            'Kode Unit',
            'Nama Unit',
            'Tipe Unit',
            'Lokasi',
            'Status',
            'Jam Buka',
            'Jam Tutup',
            'Email',
            'Telepon',
            'Kapasitas',
            'Dibuat'
        ];
    }

    private function getUnitTypeHeaders(): array
    {
        return [
            'ID',
            'Nama Tipe',
            'Deskripsi',
            'Aktif',
            'Urutan',
            'Dibuat',
            'Diperbarui'
        ];
    }
}