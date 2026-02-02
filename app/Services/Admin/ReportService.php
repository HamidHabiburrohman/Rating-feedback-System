<?php

namespace App\Services\Admin;

use App\Models\Report;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getReports($filters, $perPage = 10)
    {
        $query = Report::with(['unit', 'admin']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('visitor_ip', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $statuses = explode(',', $filters['status']);
            $query->whereIn('status', $statuses);
        }

        if (!empty($filters['tipe'])) {
            $types = explode(',', $filters['tipe']);
            $query->whereIn('tipe', $types);
        }

        if (!empty($filters['prioritas'])) {
            $priorities = explode(',', $filters['prioritas']);
            $query->whereIn('prioritas', $priorities);
        }

        if (!empty($filters['unit'])) {
            $unitIds = explode(',', $filters['unit']);
            $query->whereIn('unit_id', $unitIds);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStats()
    {
        return [
            'total' => Report::count(),
            'baru' => Report::where('status', 'baru')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'by_tipe' => Report::select('tipe', DB::raw('count(*) as total'))
                ->groupBy('tipe')
                ->get()
                ->pluck('total', 'tipe'),
            'by_prioritas' => Report::select('prioritas', DB::raw('count(*) as total'))
                ->groupBy('prioritas')
                ->get()
                ->pluck('total', 'prioritas')
        ];
    }

    public function createReport($data)
    {
        return Report::create([
            'unit_id' => $data['unit_id'] ?? null,
            'session_id' => session()->getId(),
            'visitor_ip' => request()->ip(),
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'tipe' => $data['tipe'],
            'prioritas' => $data['prioritas'] ?? 'sedang',
            'status' => 'baru',
            'lampiran' => $data['lampiran'] ?? null
        ]);
    }

    public function updateReport(Report $report, $data)
    {
        $updateData = [
            'judul' => $data['judul'] ?? $report->judul,
            'deskripsi' => $data['deskripsi'] ?? $report->deskripsi,
            'tipe' => $data['tipe'] ?? $report->tipe,
            'prioritas' => $data['prioritas'] ?? $report->prioritas,
            'status' => $data['status'] ?? $report->status,
            'unit_id' => $data['unit_id'] ?? $report->unit_id
        ];

        if (isset($data['lampiran'])) {
            $updateData['lampiran'] = $data['lampiran'];
        }

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

    public function deleteReport(Report $report)
    {
        return $report->delete();
    }

    public function getUnitsForFilter()
    {
        return Unit::where('status_aktif', true)
            ->orderBy('nama_unit')
            ->get();
    }
}