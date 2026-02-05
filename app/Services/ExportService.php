<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportService
{
    public function export($data, array $columns, array $options = [], string $format = 'excel')
    {
        $method = 'export' . ucfirst(strtolower($format));
        if (method_exists($this, $method)) {
            return $this->{$method}($data, $columns, $options);
        }
        throw new \Exception("Format export {$format} tidak didukung.");
    }

    public function exportExcel($data, array $columns, array $options = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($options['title'] ?? 'Export', 0, 31));

        $colIndex = 1;
        foreach (array_values($columns) as $columnName) {
            $cell = Coordinate::stringFromColumnIndex($colIndex) . '1';
            $sheet->setCellValue($cell, $columnName);
            $sheet->getStyle($cell)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colIndex))->setAutoSize(true);
            $colIndex++;
        }

        $rowIndex = 2;
        foreach ($data as $item) {
            $colIndex = 1;
            foreach (array_keys($columns) as $columnKey) {
                $cell = Coordinate::stringFromColumnIndex($colIndex) . $rowIndex;
                $sheet->setCellValue($cell, $this->getCellValue($item, $columnKey));
                $colIndex++;
            }
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = $this->getFilename($options) . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'export_');
        $writer->save($tempFile);
        return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf($data, array $columns, array $options = [])
    {
        $formattedData = [];
        foreach ($data as $item) {
            $row = [];
            foreach (array_keys($columns) as $column) {
                $row[] = $this->getCellValue($item, $column);
            }
            $formattedData[] = $row;
        }

        $pdf = Pdf::loadView('exports.pdf', [
            'data' => $formattedData,
            'columns' => array_values($columns),
            'title' => $options['title'] ?? 'Export Data',
            'options' => $options
        ])->setPaper('a4', $options['orientation'] ?? 'portrait');

        return $pdf->download($this->getFilename($options) . '.pdf');
    }

    public function getFilename(array $options): string
    {
        $name = $options['filename'] ?? 'export';
        return $name . '_' . date('Ymd_His');
    }

    public function getCellValue($row, string $column)
    {
        $data = is_object($row) ? $row->toArray() : $row;
        
        if (str_contains($column, '.')) {
            $keys = explode('.', $column);
            $value = $data;
            foreach ($keys as $key) {
                $value = $value[$key] ?? '';
            }
            return (string) $value;
        }

        return (string) ($data[$column] ?? '');
    }
}