<?php

namespace App\Services\Export;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    public function export(array $rows, array $headers, string $title, string $filename): Response
    {
        $response = new StreamedResponse(function () use ($rows, $headers, $title, $filename) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setTitle(substr($title, 0, 31));
            $sheet->fromArray([$headers], null, 'A1');
            
            $rowIndex = 2;
            foreach ($rows as $rowData) {
                $sheet->fromArray([array_values($rowData)], null, "A{$rowIndex}");
                $rowIndex++;
            }
            
            $this->applyStyles($sheet, count($rows), count($headers));
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        
        return $response;
    }
    
    private function applyStyles($sheet, int $dataCount, int $columnCount): void
    {
        $lastColumn = chr(64 + $columnCount);
        $lastRow = $dataCount + 1;
        
        $headerRange = "A1:{$lastColumn}1";
        $dataRange = "A1:{$lastColumn}{$lastRow}";
        
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}