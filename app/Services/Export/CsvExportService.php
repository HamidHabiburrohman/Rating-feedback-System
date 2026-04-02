<?php

namespace App\Services\Export;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportService
{
    public function export(array $rows, array $headers, string $title, string $filename): Response
    {
        $response = new StreamedResponse(function () use ($rows, $headers) {
            $file = fopen('php://output', 'w');
            
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $headers);
            
            foreach ($rows as $row) {
                fputcsv($file, array_values($row));
            }
            
            fclose($file);
        });
        
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
        
        return $response;
    }
}