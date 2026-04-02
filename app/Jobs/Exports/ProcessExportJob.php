<?php

namespace App\Jobs\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Services\Export\Contracts\ExportInterface;
use App\Models\Report;
use App\Models\Rating;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\Export;
use Illuminate\Support\Facades\Log;

class ProcessExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes
    public $tries = 3;
    public $backoff = [30, 60, 120];
    
    protected $exportLog;
    
    public function __construct(Export $exportLog)
    {
        $this->exportLog = $exportLog->withoutRelations();
    }
    
    public function handle(ExportInterface $exportManager): void
    {
        $this->exportLog->update(['status' => 'processing']);
        
        try {
            $data = $this->fetchData($this->exportLog->export_type, $this->exportLog->filters ?? []);
            
            $options = [
                'title' => $this->getTitle($this->exportLog->export_type),
                'filename' => $this->exportLog->export_type . '_export_' . $this->exportLog->id,
            ];
            
            $response = $this->processExport($exportManager, $this->exportLog->export_type, $data, $options, $this->exportLog->format);
            
            $this->saveExportResult($response, $data->count());
            
        } catch (\Exception $e) {
            $this->exportLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
    
    protected function fetchData(string $type, array $filters)
    {
        return match($type) {
            'report' => $this->fetchReports($filters),
            'rating' => $this->fetchRatings($filters),
            'unit' => $this->fetchUnits($filters),
            'unit_type' => $this->fetchUnitTypes($filters),
            default => throw new \InvalidArgumentException("Unknown export type: {$type}")
        };
    }
    
    protected function fetchReports(array $filters)
    {
        $query = Report::with(['unit', 'admin']);
        
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', "%{$filters['search']}%")
                  ->orWhere('deskripsi', 'like', "%{$filters['search']}%")
                  ->orWhere('tracking_code', 'like', "%{$filters['search']}%");
            });
        }
        
        if (!empty($filters['status'])) {
            $query->whereIn('status', explode(',', $filters['status']));
        }
        
        if (!empty($filters['unit'])) {
            $query->whereIn('unit_id', explode(',', $filters['unit']));
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        return $query->latest()->get();
    }
    
    protected function fetchRatings(array $filters)
    {
        $query = Rating::with(['unit']);
        
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('komentar', 'like', "%{$filters['search']}%")
                  ->orWhere('tracking_code', 'like', "%{$filters['search']}%");
            });
        }
        
        if (!empty($filters['status'])) {
            $query->whereIn('status', explode(',', $filters['status']));
        }
        
        if (!empty($filters['unit'])) {
            $query->whereIn('unit_id', explode(',', $filters['unit']));
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        return $query->latest()->get();
    }
    
    protected function fetchUnits(array $filters)
    {
        $query = Unit::with(['type']);
        
        if (!empty($filters['search'])) {
            $query->where('nama_unit', 'like', "%{$filters['search']}%")
                  ->orWhere('kode_unit', 'like', "%{$filters['search']}%");
        }
        
        if (!empty($filters['status'])) {
            $query->whereIn('status', explode(',', $filters['status']));
        }
        
        if (!empty($filters['type'])) {
            $query->whereIn('type_id', explode(',', $filters['type']));
        }
        
        return $query->orderBy('nama_unit')->get();
    }
    
    protected function fetchUnitTypes(array $filters)
    {
        $query = UnitType::query();
        
        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }
        
        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] == 'active');
        }
        
        return $query->orderBy('sort_order')->get();
    }
    
    protected function processExport($exportManager, string $type, $data, array $options, string $format)
    {
        return match($type) {
            'report' => $exportManager->exportReport($data, $options, $format),
            'rating' => $exportManager->exportRating($data, $options, $format),
            'unit', 'unit_type' => $this->processManualExport($type, $data, $options, $format),
        };
    }
    
    protected function processManualExport(string $type, $data, array $options, string $format)
    {
        $rows = $this->transformManualData($type, $data);
        $headers = $this->getManualHeaders($type);
        
        $exportService = match($format) {
            'pdf' => app(\App\Services\Export\PdfExportService::class),
            'csv' => app(\App\Services\Export\CsvExportService::class),
            default => app(\App\Services\Export\ExcelExportService::class),
        };
        
        return $exportService->export($rows, $headers, $options['title'], $options['filename']);
    }
    
    protected function transformManualData(string $type, $data)
    {
        return match($type) {
            'unit' => $data->map(function ($unit) {
                return [
                    $unit->id,
                    $unit->kode_unit,
                    $unit->nama_unit,
                    $unit->type->name ?? '-',
                    $unit->lokasi,
                    $unit->status,
                    $unit->jam_buka?->format('H:i') ?? '-',
                    $unit->jam_tutup?->format('H:i') ?? '-',
                    $unit->kontak_email,
                    $unit->kontak_telepon,
                    $unit->kapasitas,
                    $unit->created_at->format('d/m/Y H:i'),
                ];
            })->toArray(),
            
            'unit_type' => $data->map(function ($type) {
                return [
                    $type->id,
                    $type->name,
                    $type->description ?? '-',
                    $type->is_active ? 'Ya' : 'Tidak',
                    $type->sort_order,
                    $type->created_at->format('d/m/Y H:i'),
                    $type->updated_at->format('d/m/Y H:i'),
                ];
            })->toArray(),
        };
    }
    
    protected function getManualHeaders(string $type): array
    {
        return match($type) {
            'unit' => ['ID', 'Kode', 'Nama Unit', 'Tipe', 'Lokasi', 'Status', 'Jam Buka', 'Jam Tutup', 'Email', 'Telepon', 'Kapasitas', 'Dibuat'],
            'unit_type' => ['ID', 'Nama Tipe', 'Deskripsi', 'Aktif', 'Urutan', 'Dibuat', 'Diperbarui'],
        };
    }
    
    protected function getTitle(string $type): string
    {
        return match($type) {
            'report' => 'Laporan Pengaduan',
            'rating' => 'Rating dan Ulasan',
            'unit' => 'Data Unit',
            'unit_type' => 'Tipe Unit',
            default => 'Export Data'
        };
    }
    
    protected function saveExportResult($response, int $recordCount): void
    {
        $content = $response->getContent();
        $filename = $this->exportLog->export_type . '_export_' . $this->exportLog->id . '.' . $this->exportLog->format;
        $path = 'exports/' . $filename;
        
        Storage::put($path, $content);
        
        $this->exportLog->update([
            'filename' => $filename,
            'file_path' => $path,
            'total_records' => $recordCount,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
    
    public function failed(\Throwable $exception): void
    {
        $this->exportLog->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
        
        Log::error('Export job failed', [
            'export_log_id' => $this->exportLog->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}