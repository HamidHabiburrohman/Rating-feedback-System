<?php

namespace App\Services\Export\Exports;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Report\Report;

class ReportExport extends BaseExport
{
    protected string $exportType = 'report';
    
    protected function prepareData(Collection $data): array
    {
        return $data->map(function (Report $report) {
            return [
                'ID' => $report->id,
                'Tracking Code' => $report->tracking_code,
                'Judul' => $report->judul,
                'Deskripsi' => $report->deskripsi,
                'Tipe' => $report->tipe_label,
                'Prioritas' => $report->prioritas,
                'Status' => $report->status_label,
                'Unit' => $report->unit->nama_unit ?? '-',
                'Admin' => $report->admin->name ?? '-',
                'Tanggapan Admin' => $report->tanggapan_admin ?? '-',
                'Tanggal Dibuat' => $report->created_at->format('d/m/Y H:i')
    ];
        })->toArray();
    }
    
    protected function getHeaders(): array
    {
        return [
            'ID',
            'Tracking Code',
            'Judul',
            'Deskripsi',
            'Tipe',
            'Prioritas',
            'Status',
            'Unit',
            'Admin',
            'Tanggapan Admin',
            'Tanggal Dibuat'
        ];
    }
}