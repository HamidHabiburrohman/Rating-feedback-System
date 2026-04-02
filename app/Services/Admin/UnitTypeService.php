<?php

namespace App\Services\Admin;

use App\Models\UnitType;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnitTypeService extends BaseAdminService
{
    protected array $searchableColumns = ['name', 'description'];
    protected array $filterableColumns = ['is_active'];
    protected array $sortableColumns   = ['name', 'created_at'];
    protected string $defaultSort  = 'name';
    protected string $defaultOrder = 'asc';

    protected IconService $iconService;

    public function __construct(UnitType $unitType, IconService $iconService)
    {
        $this->model       = $unitType;
        $this->iconService = $iconService;
        parent::__construct();
    }

    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['search']) && !empty($this->searchableColumns)) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        foreach ($this->filterableColumns as $column) {
            if (isset($filters[$column]) && $filters[$column] !== '') {
                $query->where($column, $filters[$column]);
            }
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $sort  = $filters['sort'] ?? $this->defaultSort;
        $order = $filters['order'] ?? $this->defaultOrder;

        if (!in_array($sort, $this->sortableColumns)) {
            $sort = $this->defaultSort;
        }

        if (!in_array($order, ['asc', 'desc'])) {
            $order = $this->defaultOrder;
        }

        $query->orderBy($sort, $order);

        return $query;
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters)->withCount('units');

            if (!empty($filters['status'])) {
                $statuses        = explode(',', $filters['status']);
                $booleanStatuses = array_map(fn($value) => $value === '1', $statuses);
                $query->whereIn('is_active', $booleanStatuses);
            }

            return $this->executePaginate($query, $filters);

        } catch (\Exception $e) {
            Log::error('UnitTypeService::getPaginated error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
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

    public function getFilterData(): array
    {
        return [
            'statuses' => [
                ''  => 'Semua Status',
                '1' => 'Aktif',
                '0' => 'Nonaktif',
            ],
        ];
    }

    public function getStats(): array
    {
        return [
            'total'         => $this->model->count(),
            'active'        => $this->model->where('is_active', true)->count(),
            'inactive'      => $this->model->where('is_active', false)->count(),
            'with_units'    => $this->model->has('units')->count(),
            'without_units' => $this->model->doesntHave('units')->count(),
        ];
    }

    public function getMonthlyStats(): array
    {
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $counts[] = $this->model
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['months' => $months, 'counts' => $counts];
    }

    public function getAvailableIcons(): array
    {
        return $this->iconService->getIconPreviews();
    }

    public function getIconOptions(): array
    {
        return $this->iconService->getIconOptions();
    }

    public function create(array $data): UnitType
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (empty($data['icon_key']) || !$this->iconService->getIcon($data['icon_key'])) {
            $data['icon_key'] = array_key_first($this->iconService->getAllIcons());
        }

        $unitType = parent::create($data);
        $this->logAdminAction('create_unit_type', $unitType);

        return $unitType;
    }

    public function update(int $id, array $data): UnitType
    {
        $unitType = $this->findOrFail($id);

        if (isset($data['name']) && $data['name'] !== $unitType->name) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        }

        if (isset($data['icon_key']) && !$this->iconService->getIcon($data['icon_key'])) {
            unset($data['icon_key']);
        }

        unset($data['sort_order']);

        $updated = parent::update($id, $data);

        $this->logAdminAction('update_unit_type', $updated, null, [
            'changes' => array_keys($data),
        ]);

        return $updated;
    }

    public function delete(int $id): bool
    {
        $unitType = $this->findOrFail($id);

        if ($unitType->units()->exists()) {
            throw new \Exception('Tidak dapat menghapus tipe unit yang masih memiliki unit terkait.');
        }

        DB::beginTransaction();
        try {
            $unitTypeName = $unitType->name;
            $unitTypeId   = $unitType->id;

            $result = $unitType->delete();

            if ($result) {
                // Log sebelum commit — gunakan data primitif, bukan model
                // agar tidak bergantung pada state model setelah soft-delete
                $this->logAdminAction(
                    'delete_unit_type',
                    (object) ['id' => $unitTypeId, 'name' => $unitTypeName],
                );
            }

            DB::commit();
            return (bool) $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UnitTypeService::delete error', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function toggleStatus(int $id): array
    {
        $unitType  = $this->findOrFail($id);
        $oldStatus = $unitType->is_active;

        $unitType->is_active = !$unitType->is_active;
        $unitType->save();

        $this->logAdminAction('toggle_unit_type_status', $unitType, null, [
            'previous' => $oldStatus,
            'current'  => $unitType->is_active,
        ]);

        return [
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'data'    => $this->formatStatusResponse($unitType),
        ];
    }

    public function reorder(array $ids): bool
    {
        foreach ($ids as $index => $id) {
            $this->model->where('id', $id)->update(['sort_order' => $index + 1]);
        }

        $this->logAdminAction('reorder_unit_types', (object) ['count' => count($ids)]);

        return true;
    }
}
