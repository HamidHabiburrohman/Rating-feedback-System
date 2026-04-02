<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Export\ExportRequest;
use App\Models\Report;
use App\Models\Rating;
use App\Models\Unit;
use App\Models\UnitType;
use App\Services\Admin\ExportDataService;
use App\Models\Export;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected ExportDataService $service;

    public function __construct(ExportDataService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $stats = [
            'total_reports' => Report::count(),
            'total_ratings' => Rating::count(),
            'total_units' => Unit::count(),
            'total_unit_types' => UnitType::count(),
        ];

        try {
            $recentExports = Export::with('user')
                ->latest()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $recentExports = collect([]);
        }

        return view('admin.exports.index', compact('stats', 'recentExports'));
    }

    public function exportReports(ExportRequest $request)
    {
        try {
            $result = $this->service->exportReports($request->validated());
            
            if ($result instanceof \Illuminate\Contracts\View\View) {
                return $result;
            }
            
            return $result;
        } catch (\Exception $e) {
            return redirect()->route('admin.exports.index')
                ->with('error', 'Gagal mengekspor laporan: ' . $e->getMessage());
        }
    }

    public function exportRatings(ExportRequest $request)
    {
        try {
            return $this->service->exportRatings($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.exports.index')
                ->with('error', 'Gagal mengekspor rating: ' . $e->getMessage());
        }
    }

    public function exportUnits(ExportRequest $request)
    {
        try {
            return $this->service->exportUnits($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.exports.index')
                ->with('error', 'Gagal mengekspor unit: ' . $e->getMessage());
        }
    }

    public function exportUnitTypes(ExportRequest $request)
    {
        try {
            return $this->service->exportUnitTypes($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.exports.index')
                ->with('error', 'Gagal mengekspor tipe unit: ' . $e->getMessage());
        }
    }

    public function downloadExport($id)
    {
        try {
            return $this->service->downloadExport($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.exports.index')
                ->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }
}