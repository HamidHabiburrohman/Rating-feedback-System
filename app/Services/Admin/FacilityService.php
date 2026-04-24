<?php

namespace App\Services\Admin;

use App\Models\Facility;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class FacilityService extends BaseAdminService
{
    protected array $searchableColumns = ['name'];
    protected string $defaultSort = 'name';
    protected string $defaultOrder = 'asc';

    public function __construct(Facility $facility)
    {
        $this->model = $facility;
        parent::__construct();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters)->withCount('units');
            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('FacilityService::getPaginated error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);

            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? 10,
                1,
                ['path' => request()->url()]
            );
        }
    }

    // Di FacilityService.php
    public function getAvailableIcons(): array
    {
        $iconService = app(IconService::class);
        $icons = [];

        foreach ($iconService->getAllIcons() as $key => $icon) {
            $icons[$key] = $icon['name'];
        }

        return $icons;
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'with_units' => $this->model->has('units')->count(),
            'without_units' => $this->model->doesntHave('units')->count(),
            'most_used' => $this->model->withCount('units')
                ->orderByDesc('units_count')
                ->limit(5)
                ->get(['id', 'name', 'icon_key', 'units_count'])
        ];
    }

    public function getPopularFacilities(int $limit = 10): array
    {
        return $this->model->withCount('units')
            ->orderByDesc('units_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getUnits(int $facilityId): array
    {
        $facility = $this->find($facilityId);

        return $facility->units()
            ->with(['unitType', 'unitDepartment'])
            ->paginate(15)
            ->toArray();
    }

    public function export()
    {
        $facilities = $this->model->withCount('units')->orderBy('name')->get();

        $filename = 'facilities-export-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($facilities) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Nama Fasilitas', 'Icon', 'Jumlah Unit', 'Dibuat Pada']);

            foreach ($facilities as $facility) {
                fputcsv($file, [
                    $facility->name,
                    $facility->icon_key ?? '-',
                    $facility->units_count,
                    $facility->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
