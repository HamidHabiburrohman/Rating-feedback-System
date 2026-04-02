<?php

namespace App\Services\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    protected Model $model;

    protected array $searchableColumns = [];
    protected array $filterableColumns = [];
    protected string $defaultSort = 'created_at';
    protected string $defaultOrder = 'desc';
    protected array $perPageOptions = [10, 25, 50, 100];

    public function __construct()
    {
        //
    }

    protected function getBaseQuery(array $filters = [], array $with = [])
    {
        $query = $this->model->query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $this->applyFilters($query, $filters);
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

        $sort = $filters['sort'] ?? $this->defaultSort;
        $order = $filters['order'] ?? $this->defaultOrder;
        $query->orderBy($sort, $order);

        return $query;
    }

    protected function executePaginate($query, array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 10);

        if (!in_array($perPage, $this->perPageOptions)) {
            $perPage = 10;
        }

        return $query->paginate($perPage);
    }

    public function find(int $id, array $with = []): ?Model
    {
        $query = $this->model->query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->find($id);
    }

    public function findOrFail(int $id, array $with = []): Model
    {
        $query = $this->model->query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->findOrFail($id);
    }

    public function create(array $data): Model
    {
        DB::beginTransaction();
        try {
            $model = $this->model->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($this->getErrorContext('create', $e, $data)));
            throw $e;
        }
    }

    public function update(int $id, array $data): Model
    {
        DB::beginTransaction();
        try {
            $model = $this->findOrFail($id);
            $model->update($data);
            DB::commit();
            return $model->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($this->getErrorContext('update', $e, ['id' => $id, 'data' => $data])));
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $model = $this->findOrFail($id);
            $result = $model->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($this->getErrorContext('delete', $e, ['id' => $id])));
            throw $e;
        }
    }

    public function bulkDelete(array $ids): int
    {
        DB::beginTransaction();
        try {
            $count = $this->model->whereIn('id', $ids)->delete();
            DB::commit();
            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($this->getErrorContext('bulkDelete', $e, ['ids' => $ids])));
            throw $e;
        }
    }

    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    public function count(array $conditions = []): int
    {
        $query = $this->model->query();

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        return $query->count();
    }

    public function pluck(string $column, ?string $key = null): array
    {
        return $this->model->pluck($column, $key)->toArray();
    }

    public function getAll(array $columns = ['*']): array
    {
        return $this->model->all($columns)->toArray();
    }

    protected function getErrorContext(string $action, \Exception $e, array $context = []): array
    {
        return [
            'service' => static::class,
            'action' => $action,
            'error' => $e->getMessage(),
            'context' => $context,
            'trace' => $e->getTraceAsString()
        ];
    }

    protected function formatForDropdown($items, string $labelColumn = 'name', string $valueColumn = 'id'): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[$item->{$valueColumn}] = $item->{$labelColumn};
        }
        return $result;
    }

    protected function calculateTrend(int $current, int $previous): int
    {
        if ($previous === 0) {
            return $current > 0 ? 100 : 0;
        }
        return (int) round((($current - $previous) / $previous) * 100);
    }
}