<?php

namespace App\Services\Export;

use App\Services\Export\Contracts\ExportInterface;
use App\Services\Export\Exports\ReportExport;
use App\Services\Export\Exports\RatingExport;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ExportManager implements ExportInterface
{
    public function __construct(
        private ReportExport $reportExport,
        private RatingExport $ratingExport,
        private ExcelExportService $excelService,
        private PdfExportService $pdfService,
        private CsvExportService $csvService
    ) {}
    
    public function exportReport(Collection $data, array $options, string $format): Response
    {
        $exportData = $this->reportExport->prepare($data);
        
        return $this->handleExport(
            $exportData['rows'],
            $exportData['headers'],
            $options,
            $format,
            'report'
        );
    }
    
    public function exportRating(Collection $data, array $options, string $format): Response
    {
        $exportData = $this->ratingExport->prepare($data);
        
        return $this->handleExport(
            $exportData['rows'],
            $exportData['headers'],
            $options,
            $format,
            'rating'
        );
    }
    
    private function handleExport(
        array $rows, 
        array $headers, 
        array $options, 
        string $format, 
        string $type
    ): Response {
        $filename = $options['filename'] ?? "{$type}_export_" ;
        $title = $options['title'] ?? ucfirst($type) . ' Export';
        
        return match(strtolower($format)) {
            'pdf' => $this->pdfService->export($rows, $headers, $title, $filename),
            'csv' => $this->csvService->export($rows, $headers, $title, $filename),
            default => $this->excelService->export($rows, $headers, $title, $filename),
        };
    }
    
    public function download(string $filePath, string $filename): Response
    {
        return Storage::download($filePath, $filename);
    }
    
    public function getCsvService(): CsvExportService
    {
        return $this->csvService;
    }
    
    public function getExcelService(): ExcelExportService
    {
        return $this->excelService;
    }
    
    public function getPdfService(): PdfExportService
    {
        return $this->pdfService;
    }
}