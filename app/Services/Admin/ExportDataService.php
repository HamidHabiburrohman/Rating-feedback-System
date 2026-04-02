<?php

namespace App\Services\Admin;

use App\Models\Report;
use App\Models\Rating;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\Export;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\View\View;

class ExportDataService extends BaseAdminService
{
    protected Report $report;
    protected Rating $rating;
    protected Unit $unit;
    protected UnitType $unitType;
    protected Export $export;

    public function __construct(
        Report $report,
        Rating $rating,
        Unit $unit,
        UnitType $unitType,
        Export $export
    ) {
        $this->report = $report;
        $this->rating = $rating;
        $this->unit = $unit;
        $this->unitType = $unitType;
        $this->export = $export;
    }

    public function exportReports(array $filters)
    {
        $query = $this->report->with(['unit', 'student', 'rating']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $reports = $query->get();
        $format = $filters['format'] ?? 'csv';

        $export = $this->saveExportRecord('reports', $format, $filters, $reports->count());

        if ($format === 'csv') {
            return $this->exportReportsCsv($reports, $export);
        } elseif ($format === 'excel') {
            return $this->exportReportsCsv($reports, $export);
        } elseif ($format === 'print') {
            return view('admin.exports.reports-print', compact('reports', 'filters'));
        }

        return $this->exportReportsCsv($reports, $export);
    }

    public function exportRatings(array $filters): Response
    {
        $query = $this->rating->with(['unit', 'student', 'reports']);

        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['min_score'])) {
            $query->where('overall_score', '>=', $filters['min_score']);
        }

        if (!empty($filters['max_score'])) {
            $query->where('overall_score', '<=', $filters['max_score']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $ratings = $query->get();
        $format = $filters['format'] ?? 'csv';

        $export = $this->saveExportRecord('ratings', $format, $filters, $ratings->count());

        if ($format === 'csv') {
            return $this->exportRatingsCsv($ratings, $export);
        } elseif ($format === 'excel') {
            return $this->exportRatingsCsv($ratings, $export);
        }

        return $this->exportRatingsCsv($ratings, $export);
    }

    public function exportUnits(array $filters): Response
    {
        $query = $this->unit->with(['type', 'department']);

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if (!empty($filters['type_id'])) {
            $query->where('unit_type_id', $filters['type_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('code', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('location', 'LIKE', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $units = $query->get();
        $format = $filters['format'] ?? 'csv';

        $export = $this->saveExportRecord('units', $format, $filters, $units->count());

        if ($format === 'csv') {
            return $this->exportUnitsCsv($units, $export);
        } elseif ($format === 'excel') {
            return $this->exportUnitsCsv($units, $export);
        }

        return $this->exportUnitsCsv($units, $export);
    }

    public function exportUnitTypes(array $filters): Response
    {
        $query = $this->unitType->withCount('units');

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'LIKE', "%{$filters['search']}%");
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $unitTypes = $query->get();
        $format = $filters['format'] ?? 'csv';

        $export = $this->saveExportRecord('unit-types', $format, $filters, $unitTypes->count());

        if ($format === 'csv') {
            return $this->exportUnitTypesCsv($unitTypes, $export);
        } elseif ($format === 'excel') {
            return $this->exportUnitTypesCsv($unitTypes, $export);
        }

        return $this->exportUnitTypesCsv($unitTypes, $export);
    }

    protected function saveExportRecord(string $type, string $format, array $filters, int $recordCount): Export
    {
        $fileName = $this->generateFileName($type, $format);

        return $this->export->create([
            'user_id' => Auth::id(),
            'export_type' => $type,
            'format' => $format,
            'file_name' => $fileName,
            'file_path' => 'exports/' . $fileName,
            'status' => 'processing',
            'filters' => $filters,
            'completed_at' => null
        ]);
    }

    protected function generateFileName(string $type, string $format): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = $format === 'excel' ? 'xlsx' : 'csv';
        return "{$type}-export-{$timestamp}.{$extension}";
    }

    protected function exportReportsCsv($reports, Export $export): Response
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$export->file_name}",
        ];

        $callback = function () use ($reports, $export) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Tracking Code',
                'Title',
                'Unit',
                'Student',
                'Priority',
                'Status',
                'Admin Response',
                'Created At'
            ]);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->tracking_code,
                    $report->title,
                    $report->unit->name ?? '-',
                    $report->student->name ?? '-',
                    $report->priority,
                    $report->status,
                    $report->admin_response ?? '-',
                    $report->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);

            $export->update([
                'status' => 'completed',
                'file_size' => ob_get_length(),
                'completed_at' => now()
            ]);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportRatingsCsv($ratings, Export $export): Response
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$export->file_name}",
        ];

        $callback = function () use ($ratings, $export) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Tracking Code',
                'Unit',
                'Student',
                'Overall Score',
                'Comment',
                'Status',
                'Has Reports',
                'Created At'
            ]);

            foreach ($ratings as $rating) {
                fputcsv($file, [
                    $rating->tracking_code,
                    $rating->unit->name ?? '-',
                    $rating->student->name ?? '-',
                    $rating->overall_score,
                    $rating->comment ?? '-',
                    $rating->status,
                    $rating->reports && $rating->reports->isNotEmpty() ? 'Yes' : 'No',
                    $rating->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);

            $export->update([
                'status' => 'completed',
                'file_size' => ob_get_length(),
                'completed_at' => now()
            ]);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportUnitsCsv($units, Export $export): Response
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$export->file_name}",
        ];

        $callback = function () use ($units, $export) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Code',
                'Name',
                'Type',
                'Department',
                'Location',
                'Building',
                'Floor',
                'Phone',
                'Email',
                'Capacity',
                'Avg Rating',
                'Total Ratings',
                'Status',
                'Open Time',
                'Close Time',
                'Created At'
            ]);

            foreach ($units as $unit) {
                fputcsv($file, [
                    $unit->code,
                    $unit->name,
                    $unit->type->name ?? '-',
                    $unit->department->name ?? '-',
                    $unit->location ?? '-',
                    $unit->building ?? '-',
                    $unit->floor ?? '-',
                    $unit->phone ?? '-',
                    $unit->email ?? '-',
                    $unit->capacity ?? '-',
                    number_format($unit->avg_rating, 1),
                    $unit->total_ratings,
                    $unit->is_active ? 'Active' : 'Inactive',
                    $unit->open_time ? $unit->open_time->format('H:i') : '-',
                    $unit->close_time ? $unit->close_time->format('H:i') : '-',
                    $unit->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);

            $export->update([
                'status' => 'completed',
                'file_size' => ob_get_length(),
                'completed_at' => now()
            ]);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportUnitTypesCsv($unitTypes, Export $export): Response
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$export->file_name}",
        ];

        $callback = function () use ($unitTypes, $export) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Name',
                'Slug',
                'Icon',
                'Description',
                'Units Count',
                'Status',
                'Created At'
            ]);

            foreach ($unitTypes as $type) {
                fputcsv($file, [
                    $type->name,
                    $type->slug,
                    $type->icon_key ?? '-',
                    $type->description ?? '-',
                    $type->units_count,
                    $type->is_active ? 'Active' : 'Inactive',
                    $type->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);

            $export->update([
                'status' => 'completed',
                'file_size' => ob_get_length(),
                'completed_at' => now()
            ]);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPrintView(array $filters): View
    {
        $reports = $this->report->with(['unit', 'student'])
            ->when(!empty($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['date_from']), fn($q) => $q->whereDate('created_at', '>=', $filters['date_from']))
            ->when(!empty($filters['date_to']), fn($q) => $q->whereDate('created_at', '<=', $filters['date_to']))
            ->get();

        return view('admin.exports.reports-print', compact('reports', 'filters'));
    }

    public function downloadExport($id): Response
    {
        $export = $this->export->findOrFail($id);

        $filePath = storage_path("app/{$export->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, $export->file_name);
    }
}