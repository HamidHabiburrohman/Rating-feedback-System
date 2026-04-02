<?php

namespace App\Services\Export;

use App\Models\Report\Report;
use App\Models\Rating\Rating;
use Carbon\Carbon;

class ReportGeneratorService
{
    public function generateReportSummary(array $filters): array
    {
        $dateFrom = $filters['date_from'] ?? Carbon::now()->subMonth();
        $dateTo = $filters['date_to'] ?? Carbon::now();
        
        $reports = Report::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        $ratings = Rating::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        
        return [
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'reports' => [
                'total' => $reports->count(),
                'by_status' => $reports->groupBy('status')->map->count(),
                'by_priority' => $reports->groupBy('prioritas')->map->count(),
                'by_type' => $reports->groupBy('tipe')->map->count(),
            ],
            'ratings' => [
                'total' => $ratings->count(),
                'by_status' => $ratings->groupBy('status')->map->count(),
            ],
        ];
    }
}