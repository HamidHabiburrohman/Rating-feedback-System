<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class SettingService extends BaseAdminService
{
    protected array $searchableColumns = ['key', 'label', 'description'];
    protected array $filterableColumns = ['group', 'is_public'];
    protected string $defaultSort = 'group';
    protected string $defaultOrder = 'asc';

    public function __construct(Setting $setting)
    {
        $this->model = $setting;
        parent::__construct();
    }

    public function fetch(string $key, $default = null)
    {
        try {
            $setting = $this->model->where('key', $key)->first();
            return $setting ? $setting->typed_value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    public function getValue(string $key, $default = null)
    {
        return $this->fetch($key, $default);
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters);
            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('SettingService::getPaginated error', [
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
        $byGroup = $this->model->selectRaw('group, count(*) as total')
            ->groupBy('group')
            ->orderBy('group')
            ->getQuery()
            ->get();

        return [
            'total' => $this->model->count(),
            'by_group' => $byGroup,
            'editable' => $this->model->where('is_editable', true)->count(),
            'public' => $this->model->where('is_public', true)->count()
        ];
    }

    public function getFormData(): array
    {
        return [
            'types' => [
                'string' => 'String',
                'integer' => 'Integer',
                'boolean' => 'Boolean',
                'json' => 'JSON',
                'float' => 'Float',
                'text' => 'Text',
                'array' => 'Array'
            ],
            'groups' => $this->model->distinct()
                ->pluck('group')
                ->filter()
                ->values()
                ->toArray(),
            'type_options' => [
                'string' => ['max:255'],
                'text' => [],
                'integer' => ['integer', 'min:-2147483648', 'max:2147483647'],
                'float' => ['numeric'],
                'boolean' => ['boolean'],
                'json' => ['json'],
                'array' => ['array']
            ]
        ];
    }

    public function getGroups(): array
    {
        $groups = $this->model->select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();

        $result = [];
        foreach ($groups as $group) {
            if ($group) {
                $result[$group] = ucfirst(str_replace('_', ' ', $group));
            }
        }

        return $result;
    }

    public function getSettingsByGroup(string $group): array
    {
        $settings = $this->model->where('group', $group)
            ->orderBy('sort_order')
            ->getQuery()
            ->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[] = [
                'id' => $setting->id,
                'key' => $setting->key,
                'value' => $setting->typed_value,
                'type' => $setting->type,
                'label' => $setting->label,
                'description' => $setting->description,
                'options' => $setting->options,
                'is_editable' => $setting->is_editable
            ];
        }

        return $result;
    }

    public function getPublicSettings(): array
    {
        $settings = $this->model->where('is_public', true)
            ->orderBy('group')
            ->orderBy('sort_order')
            ->getQuery()
            ->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = [
                'value' => $setting->typed_value,
                'label' => $setting->label,
                'type' => $setting->type
            ];
        }

        return $result;
    }

    public function bulkUpdate(array $settings): array
    {
        $count = 0;
        $errors = [];

        foreach ($settings as $item) {
            try {
                if (isset($item['id'])) {
                    $setting = $this->model->find($item['id']);
                    if ($setting) {
                        $setting->update(['value' => $item['value'] ?? null]);
                        $count++;
                    }
                } elseif (isset($item['key'])) {
                    $setting = $this->model->updateOrCreate(
                        ['key' => $item['key']],
                        ['value' => $item['value'] ?? null]
                    );
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'id' => $item['id'] ?? $item['key'] ?? null,
                    'error' => $e->getMessage()
                ];
            }
        }

        $this->logAdminAction('bulk_update_settings', (object)['count' => $count, 'errors' => count($errors)]);

        return [
            'success' => true,
            'message' => "{$count} pengaturan berhasil diperbarui",
            'errors' => $errors
        ];
    }

    public function resetToDefault(string $key): array
    {
        $setting = $this->model->where('key', $key)->first();

        if (!$setting) {
            return ['success' => false, 'message' => 'Pengaturan tidak ditemukan'];
        }

        $setting->update(['value' => null]);

        $this->logAdminAction('reset_setting', $setting);

        return [
            'success' => true,
            'message' => 'Pengaturan berhasil direset ke default',
            'value' => $setting->typed_value
        ];
    }

    public function importFromConfig(array $config): int
    {
        $count = 0;

        foreach ($config as $key => $value) {
            try {
                $this->model->updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => is_array($value) ? json_encode($value) : $value,
                        'type' => $this->determineType($value)
                    ]
                );
                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->logAdminAction('import_settings', (object)['count' => $count]);

        return $count;
    }

    protected function determineType($value): string
    {
        if (is_array($value)) {
            return 'json';
        }
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value)) {
            return 'integer';
        }
        if (is_float($value)) {
            return 'float';
        }
        return 'string';
    }

    public function export()
    {
        $settings = $this->model->orderBy('group')
            ->orderBy('sort_order')
            ->getQuery()
            ->get();

        $filename = 'settings-export-' . date('Y-m-d') . '.json';

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $data = [];
        foreach ($settings as $setting) {
            $data[] = [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'group' => $setting->group,
                'label' => $setting->label,
                'description' => $setting->description,
                'options' => $setting->options,
                'is_public' => $setting->is_public,
                'sort_order' => $setting->sort_order
            ];
        }

        return response()->json($data, 200, $headers);
    }

    public function find(int $id, array $with = []): ?Setting
    {
        return parent::find($id, $with);
    }

    public function findOrFail(int $id, array $with = []): Setting
    {
        return parent::findOrFail($id, $with);
    }

    public function findByKey(string $key): ?Setting
    {
        return $this->model->where('key', $key)->first();
    }

    public function findByIdOrKey($id): ?Setting
    {
        if (is_numeric($id)) {
            return $this->find((int)$id);
        }
        return $this->findByKey($id);
    }
}