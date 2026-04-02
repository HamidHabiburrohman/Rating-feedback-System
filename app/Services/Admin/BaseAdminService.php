<?php

namespace App\Services\Admin;

use App\Models\ModerationLog;
use App\Services\Shared\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseAdminService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }
    protected function getAdminId(): ?int
    {
        return Auth::id();
    }

    protected function logAdminAction(string $action, $target, ?string $reason = null, array $metadata = []): void
    {
        try {
            ModerationLog::log($this->getAdminId(), $action, $target, $reason, $metadata);
        } catch (\Exception $e) {
            report($e);
        }
    }

    protected function formatStatusResponse($model, string $type = 'status'): array
    {
        $isActive = $model->is_active ?? $model->status === 'active';
        return [
            'id' => $model->id,
            'is_active' => $isActive,
            'status_text' => $isActive ? 'Aktif' : 'Nonaktif',
            'status_class' => $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger',
            'updated_at' => $model->updated_at?->toISOString()
        ];
    }

    protected function getFilterOptions(array $customOptions = []): array
    {
        return array_merge([
            'per_page' => $this->perPageOptions,
            'sort_options' => ['asc', 'desc']
        ], $customOptions);
    }

    /**
     * Override delete method with better error handling
     */
    public function delete(int $id): bool
    {
        try {
            $model = $this->find($id);
            
            if (!$model) {
                Log::warning('Delete failed: Model not found', [
                    'model' => get_class($this->model),
                    'id' => $id
                ]);
                return false;
            }
            
            // Perform delete
            $result = $model->delete();
            
            if ($result) {
                Log::info('Model deleted successfully', [
                    'model' => get_class($this->model),
                    'id' => $id
                ]);
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Delete error in BaseAdminService', [
                'model' => get_class($this->model),
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}