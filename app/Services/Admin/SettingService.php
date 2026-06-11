<?php

namespace App\Services\Admin;

use App\Models\System\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingService extends BaseAdminService
{
    public function getAll(): array
    {
        return Cache::tags(['settings'])->remember('all_settings', 86400, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function getGrouped(): array
    {
        return Cache::tags(['settings'])->remember('grouped_settings', 86400, function () {
            return Setting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group')->toArray();
        });
    }

    public function updateMany(array $settings, int $adminId): bool
    {
        return DB::transaction(function () use ($settings, $adminId) {
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'updated_by' => $adminId]
                );
            }
            
            Cache::tags(['settings'])->flush();
            return true;
        });
    }

    public function get(string $key, $default = null)
    {
        $settings = $this->getAll();
        return $settings[$key] ?? $default;
    }
}