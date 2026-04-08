<?php

namespace App\Services\Admin;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitDepartment;
use App\Models\Facility;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnitService extends BaseAdminService
{
    protected array $searchableColumns = ['code', 'name', 'description', 'location'];
    protected array $filterableColumns = ['unit_type_id', 'unit_department_id', 'is_active', 'operational_status'];

    public function __construct(Unit $unit)
    {
        $this->model = $unit;
        parent::__construct();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters, ['type', 'department', 'primaryPhoto']);

            if (!empty($filters['with_trashed']) && $filters['with_trashed'] === 'true') {
                $query->withTrashed();
            }

            if (!empty($filters['only_trashed']) && $filters['only_trashed'] === 'true') {
                $query->onlyTrashed();
            }

            if (!empty($filters['status'])) {
                $statuses = explode(',', $filters['status']);
                $query->whereIn('operational_status', $statuses);
            }

            if (!empty($filters['type'])) {
                $types = explode(',', $filters['type']);
                $query->whereHas('type', fn($q) => $q->whereIn('name', $types));
            }

            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('UnitService::getPaginated error', [
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

    public function getTrashed(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->model->onlyTrashed()
                ->with(['type', 'department']);

            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    foreach ($this->searchableColumns as $column) {
                        $q->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
            }

            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('UnitService::getTrashed error', [
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

    public function getTypeNames(): array
    {
        return UnitType::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();
    }

    public function getCreateData(): array
    {
        return [
            'unitTypes' => UnitType::where('is_active', true)->orderBy('name')->get(),
            'unitDepartments' => UnitDepartment::where('is_active', true)->orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
        ];
    }

    public function getEditData(int|string $id): array
    {
        $unit = $this->find($id, ['facilities', 'photos']);

        return [
            'unit' => $unit,
            'unitTypes' => UnitType::where('is_active', true)->orderBy('name')->get(),
            'unitDepartments' => UnitDepartment::where('is_active', true)->orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
            'selectedFacilities' => $unit->facilities->pluck('id')->toArray(),
        ];
    }

    public function create(array $data): Unit
    {
        DB::beginTransaction();
        try {
            $data['slug'] = Str::slug($data['name']);

            $openDaysStart = $data['open_days_start'] ?? 'monday';
            $openDaysEnd = $data['open_days_end'] ?? 'friday';
            unset($data['open_days_start'], $data['open_days_end']);

            $unit = parent::create($data);

            $unit->open_days_start = $openDaysStart;
            $unit->open_days_end = $openDaysEnd;
            $unit->save();

            if (isset($data['facilities'])) {
                $unit->facilities()->sync($data['facilities']);
            }

            DB::commit();
            return $unit;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UnitService::create error', [
                'message' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int|string $id, array $data): Unit
    {
        DB::beginTransaction();
        try {
            $unit = $this->find($id);

            if (isset($data['name']) && $data['name'] !== $unit->name) {
                $data['slug'] = Str::slug($data['name']);
            }

            $openDaysStart = $data['open_days_start'] ?? $unit->open_days_start;
            $openDaysEnd = $data['open_days_end'] ?? $unit->open_days_end;
            unset($data['open_days_start'], $data['open_days_end']);

            $unit = parent::update((int) $id, $data);

            $unit->open_days_start = $openDaysStart;
            $unit->open_days_end = $openDaysEnd;
            $unit->save();

            if (isset($data['facilities'])) {
                $unit->facilities()->sync($data['facilities']);
            }

            DB::commit();
            return $unit;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UnitService::update error', [
                'message' => $e->getMessage(),
                'id' => $id,
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function delete(int|string $id): bool
    {
        DB::beginTransaction();
        try {
            $unit = $this->find($id);

            if (!$unit) {
                throw new \Exception('Unit tidak ditemukan');
            }

            $result = $unit->delete();

            if ($result) {
                $this->logAdminAction('delete_unit', $unit, null, [
                    'unit_id' => (int) $id,
                    'unit_name' => $unit->name,
                ]);
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UnitService::delete error', [
                'message' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function forceDelete(int|string $id): bool
    {
        DB::beginTransaction();
        try {
            $unit = $this->model->withTrashed()->find((int) $id);

            if (!$unit) {
                throw new \Exception('Unit tidak ditemukan');
            }

            $result = $unit->forceDelete();

            if ($result) {
                $this->logAdminAction('force_delete_unit', (object)['id' => (int) $id], null, [
                    'unit_id' => (int) $id,
                    'unit_name' => $unit->name,
                ]);
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UnitService::forceDelete error', [
                'message' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function restore(int|string $id): bool
    {
        DB::beginTransaction();
        try {
            $unit = $this->model->withTrashed()->find((int) $id);

            if (!$unit) {
                throw new \Exception('Unit tidak ditemukan');
            }

            $result = $unit->restore();

            if ($result) {
                $this->logAdminAction('restore_unit', $unit, null, [
                    'unit_id' => (int) $id,
                    'unit_name' => $unit->name,
                ]);
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UnitService::restore error', [
                'message' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function find(int|string $id, array $with = []): ?Unit
    {
        $query = $this->model->query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->find((int) $id);
    }

    public function findWithTrashed(int|string $id, array $with = []): ?Unit
    {
        $query = $this->model->withTrashed();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->find((int) $id);
    }

    public function findOrFail(int|string $id, array $with = []): Unit
    {
        $query = $this->model->query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->findOrFail((int) $id);
    }

    public function findOrFailWithTrashed(int|string $id, array $with = []): Unit
    {
        $query = $this->model->withTrashed();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->findOrFail((int) $id);
    }

    public static function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'open' => 'bg-success-subtle text-success',
            'full' => 'bg-warning-subtle text-warning',
            'maintenance' => 'bg-info-subtle text-info',
            'closed' => 'bg-danger-subtle text-danger',
            default => 'bg-secondary-subtle text-secondary',
        };
    }

    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            'open' => 'Open',
            'full' => 'Full',
            'maintenance' => 'Maintenance',
            'closed' => 'Closed',
            default => ucfirst($status),
        };
    }

    public static function getProgressColor(int $capacityPercent): string
    {
        return match (true) {
            $capacityPercent >= 90 => 'bg-danger',
            $capacityPercent >= 70 => 'bg-warning',
            default => 'bg-primary',
        };
    }

    public static function getActiveStatusBadge(bool $isActive): array
    {
        return $isActive
            ? ['label' => 'Aktif', 'class' => 'bg-success-subtle text-success']
            : ['label' => 'Nonaktif', 'class' => 'bg-danger-subtle text-danger'];
    }
}