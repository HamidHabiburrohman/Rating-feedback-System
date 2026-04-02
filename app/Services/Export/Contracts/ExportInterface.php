<?php

namespace App\Services\Export\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

interface ExportInterface
{
    public function exportReport(Collection $data, array $options, string $format): Response;
    
    public function exportRating(Collection $data, array $options, string $format): Response;
    
    public function download(string $filePath, string $filename): Response;
    
    public function getCsvService();
    
    public function getExcelService();
    
    public function getPdfService();
}