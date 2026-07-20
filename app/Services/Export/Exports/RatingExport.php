<?php

namespace App\Services\Export\Exports;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Rating\Rating;

class RatingExport extends BaseExport
{
    protected string $exportType = 'rating';
    
    protected function prepareData(Collection $data): array
    {
        return $data->map(function (Rating $rating) {
            return [
                'ID' => $rating->id,
                'Tracking Code' => $rating->tracking_code,
                'Unit' => $rating->unit->nama_unit ?? '-',
                'Komentar' => $rating->komentar,
                'Status' => $rating->status_label,
                'Status Warna' => $rating->status_warna,
                'IP Address' => $rating->visitor_ip ?? '-',
                'Metadata' => json_encode($rating->metadata ?? []),
                'Tanggal Dibuat' => $rating->created_at->format('d/m/Y H:i'),
                'Tanggal Dibalas' => $rating->dibalas_pada ? $rating->dibalas_pada->format('d/m/Y H:i') : '-'
    ];
        })->toArray();
    }
    
    protected function getHeaders(): array
    {
        return [
            'ID',
            'Tracking Code',
            'Unit',
            'Komentar',
            'Status',
            'Status Warna',
            'IP Address',
            'Metadata',
            'Tanggal Dibuat',
            'Tanggal Dibalas'
        ];
    }
    
    protected function getSummary(Collection $data): array
    {
        return [
            'total' => $data->count(),
            'by_status' => [
                'pending' => $data->where('status', 'pending')->count(),
                'dibalas' => $data->where('status', 'dibalas')->count(),
                'selesai' => $data->where('status', 'selesai')->count(),
            ]
        ];
    }
}