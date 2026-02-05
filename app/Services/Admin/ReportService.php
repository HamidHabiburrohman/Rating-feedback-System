<?php

namespace App\Services\Admin;

use App\Models\Report;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportService
{
    public function getReports(array $filters = [], int $perPage = 10)
    {
        $query = Report::with(['unit', 'admin']);

        if (isset($filters['search'])) {
            $this->applySearchFilter($query, $filters['search']);
        }

        if (isset($filters['status'])) {
            $this->applyStatusFilter($query, $filters['status']);
        }

        if (isset($filters['tipe'])) {
            $this->applyTypeFilter($query, $filters['tipe']);
        }

        if (isset($filters['unit'])) {
            $this->applyUnitFilter($query, $filters['unit']);
        }

        if (isset($filters['prioritas'])) {
            $this->applyPriorityFilter($query, $filters['prioritas']);
        }

        if (isset($filters['date_from']) || isset($filters['date_to'])) {
            $this->applyDateFilter($query, $filters['date_from'] ?? null, $filters['date_to'] ?? null);
        }

        if (isset($filters['per_page']) && $filters['per_page'] === 'all') {
            return $query->latest()->get();
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function createReport(array $data)
    {
        return Report::create([
            'tracking_code' => 'REP-' . strtoupper(Str::random(8)),
            'unit_id' => $data['unit_id'] ?? null,
            'visitor_session_id' => session()->getId(),
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'tipe' => $data['tipe'],
            'prioritas' => $data['prioritas'] ?? 'sedang',
            'status' => 'baru',
        ]);
    }

    public function updateReport(Report $report, array $data)
    {
        $allowedFields = ['judul', 'deskripsi', 'tipe', 'prioritas', 'status', 'unit_id', 'lampiran'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        return $report->update($updateData);
    }

    public function updateStatus(Report $report, $status, $adminId = null, $tanggapan = null)
    {
        $updateData = ['status' => $status];

        if ($status === 'diproses' && $adminId) {
            $updateData['admin_id'] = $adminId;
        }

        if ($tanggapan) {
            $updateData['tanggapan_admin'] = $tanggapan;
            $updateData['ditanggapi_pada'] = now();
        }

        return $report->update($updateData);
    }

    public function getStats()
    {
        return [
            'total' => Report::count(),
            'baru' => Report::where('status', 'baru')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'by_tipe' => Report::groupBy('tipe')->select('tipe', DB::raw('count(*) as total'))->pluck('total', 'tipe'),
            'by_prioritas' => Report::groupBy('prioritas')->select('prioritas', DB::raw('count(*) as total'))->pluck('total', 'prioritas')
        ];
    }

    public function getUnitsForFilter()
    {
        return Unit::where('status_aktif', true)->orderBy('nama_unit')->get();
    }

    public function deleteReport(Report $report)
    {
        return $report->delete();
    }

    private function applySearchFilter($query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'LIKE', "%{$search}%")
              ->orWhere('deskripsi', 'LIKE', "%{$search}%")
              ->orWhere('tracking_code', 'LIKE', "%{$search}%");
        });
    }

    private function applyStatusFilter($query, string $status): void
    {
        $statusFilters = explode(',', $status);
        $query->whereIn('status', $statusFilters);
    }

    private function applyTypeFilter($query, string $type): void
    {
        $typeFilters = explode(',', $type);
        $query->whereIn('tipe', $typeFilters);
    }

    private function applyUnitFilter($query, string $unit): void
    {
        $unitFilters = explode(',', $unit);
        $query->whereIn('unit_id', $unitFilters);
    }

    private function applyPriorityFilter($query, string $priority): void
    {
        $priorityFilters = explode(',', $priority);
        $query->whereIn('prioritas', $priorityFilters);
    }

    private function applyDateFilter($query, ?string $dateFrom, ?string $dateTo): void
    {
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
    }
}