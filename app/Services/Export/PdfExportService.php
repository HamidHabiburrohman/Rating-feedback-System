<?php

namespace App\Services\Export;

use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class PdfExportService
{
    public function export(array $rows, array $headers, string $title, string $filename): Response
    {
        $data = [
            'title' => $title,
            'headers' => $headers,
            'rows' => $rows,
            'summary' => $this->generateSummary($rows),
            'print_date' => date('d F Y H:i:s')
    ];

        $pdf = Pdf::loadView('exports.pdf', $data);
        
        return $pdf->download($filename . '.pdf');
        
    }

    private function generateSummary(array $rows): array
    {
        return [
            'Total Records' => count($rows),
            'Generated Date' => date('d/m/Y H:i')
    ];
    }
}