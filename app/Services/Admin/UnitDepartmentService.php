<?php

namespace App\Services\Admin;

use App\Models\UnitDepartment;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnitDepartmentService extends BaseAdminService
{
    /**
     * @var UnitDepartment
     */


    protected array $searchableColumns = ['name', 'code', 'description'];
    protected array $filterableColumns = ['is_active'];
    protected string $defaultSort = 'name';
    protected string $defaultOrder = 'asc';

    public function __construct(UnitDepartment $unitDepartment)
    {
        $this->model = $unitDepartment;
        parent::__construct();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters)->withCount('units');
            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {

        Log::error('UnitDepartmentService::getPaginated error', [
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

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'with_units' => $this->model->has('units')->count(),
            'without_units' => $this->model->doesntHave('units')->count()
        ];
    }

    public function getForFilter(): array
    {
        return [
            'statuses' => [
                '' => 'Semua Status',
                '1' => 'Aktif',
                '0' => 'Nonaktif'
            ]
        ];
    }

    public function create(array $data): UnitDepartment
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        return parent::create($data);
    }

    public function update(int $id, array $data): UnitDepartment
    {
        $department = $this->find($id);

        if (isset($data['name']) && $data['name'] !== $department->name) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        }

        return parent::update($id, $data);
    }

    public function delete(int $id): bool
    {
        $department = $this->find($id);

        if ($department && $department->units()->count() > 0) {
            throw new \Exception('Tidak dapat menghapus departemen yang masih memiliki unit');
        }

        return parent::delete($id);
    }

    public function toggleStatus(int $id): array
    {
        $department = $this->findOrFail($id);
        $department->is_active = !$department->is_active;
        $department->save();

        $this->logAdminAction('toggle_department_status', $department, null, [
            'previous' => !$department->is_active,
            'current' => $department->is_active
        ]);

        return $this->formatStatusResponse($department);
    }

    public function find(int $id, array $with = []): ?UnitDepartment
    {
        return parent::find($id, $with);
    }

    public function findOrFail(int $id, array $with = []): UnitDepartment
    {
        return parent::findOrFail($id, $with);
    }
}
