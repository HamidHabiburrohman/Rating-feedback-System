<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Models\Report;
use App\Models\Rating;
use App\Models\Unit;
use App\Models\UnitType;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $exportService;
    
    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }
    
    public function exportReports(Request $request)
    {
        $reports = Report::with(['unit', 'admin'])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                $query->whereIn('status', explode(',', $status));
            })
            ->when($request->tipe, function($query, $tipe) {
                $query->whereIn('tipe', explode(',', $tipe));
            })
            ->when($request->prioritas, function($query, $prioritas) {
                $query->whereIn('prioritas', explode(',', $prioritas));
            })
            ->when($request->unit, function($query, $unit) {
                $query->whereIn('unit_id', explode(',', $unit));
            })
            ->when($request->date_from, function($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->get();
        
        $columns = [
            'id' => 'ID',
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'tipe' => 'Tipe',
            'prioritas' => 'Prioritas',
            'status' => 'Status',
            'unit.nama_unit' => 'Unit',
            'admin.name' => 'Admin',
            'tanggapan_admin' => 'Tanggapan',
            'created_at' => 'Tanggal Dibuat'
        ];
        
        $options = [
            'title' => 'Laporan Pengaduan',
            'filename' => 'laporan_reports',
            'summary' => [
                'Total Laporan' => $reports->count(),
                'Laporan Baru' => $reports->where('status', 'baru')->count(),
                'Selesai' => $reports->where('status', 'selesai')->count()
            ]
        ];
        
        return $this->exportService->export($reports, $columns, $options, $request->get('format', 'excel'));
    }
    
    public function exportRatings(Request $request)
    {
        $ratings = Rating::with(['unit'])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('komentar', 'like', "%{$search}%")
                      ->orWhereHas('unit', fn($qu) => $qu->where('nama_unit', 'like', "%{$search}%"));
                });
            })
            ->when($request->status, fn($q, $s) => $q->whereIn('status', explode(',', $s)))
            ->when($request->date_from, fn($q, $df) => $q->whereDate('created_at', '>=', $df))
            ->when($request->date_to, fn($q, $dt) => $q->whereDate('created_at', '<=', $dt))
            ->latest()
            ->get();
        
        $columns = [
            'id' => 'ID',
            'unit.nama_unit' => 'Unit',
            'komentar' => 'Komentar',
            'status' => 'Status',
            'visitor_ip' => 'IP Address',
            'created_at' => 'Tanggal'
        ];
        
        $options = [
            'title' => 'Rating & Ulasan',
            'filename' => 'rating_export'
        ];
        
        return $this->exportService->export($ratings, $columns, $options, $request->get('format', 'excel'));
    }
    
    public function exportUnits(Request $request)
    {
        $units = Unit::with(['type'])
            ->when($request->search, function($query, $search) {
                $query->where('nama_unit', 'like', "%{$search}%")
                      ->orWhere('kode_unit', 'like', "%{$search}%");
            })
            ->when($request->status, fn($q, $s) => $q->whereIn('status', explode(',', $s)))
            ->when($request->type, fn($q, $t) => $q->whereIn('type_id', explode(',', $t)))
            ->latest()
            ->get();
        
        $columns = [
            'id' => 'ID',
            'kode_unit' => 'Kode',
            'nama_unit' => 'Nama Unit',
            'type.name' => 'Tipe',
            'status' => 'Status',
            'created_at' => 'Dibuat'
        ];
        
        $options = ['title' => 'Data Unit', 'filename' => 'units_export'];
        
        return $this->exportService->export($units, $columns, $options, $request->get('format', 'excel'));
    }
    
    public function exportUnitTypes(Request $request)
    {
        $types = UnitType::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('is_active', $status == 'active');
            })
            ->orderBy('sort_order')
            ->get();
        
        $columns = [
            'id' => 'ID',
            'name' => 'Nama Tipe',
            'is_active' => 'Aktif',
            'sort_order' => 'Urutan'
        ];
        
        $options = ['title' => 'Tipe Unit', 'filename' => 'unit_types_export'];
        
        return $this->exportService->export($types, $columns, $options, $request->get('format', 'excel'));
    }
}