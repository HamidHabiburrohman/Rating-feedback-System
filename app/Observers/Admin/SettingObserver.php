<?php

namespace App\Observers\Admin;

use App\Models\Setting;
use App\Models\ModerationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingObserver
{
    public function created(Setting $setting): void
    {
        $this->clearCache();
        $this->logActivity('created', $setting);
    }

    public function updated(Setting $setting): void
    {
        $this->clearCache();
        
        if ($setting->isDirty('value')) {
            $this->logActivity('value_changed', $setting, [
                'old' => $setting->getOriginal('value'),
                'new' => $setting->value,
            ]);
        }

        if ($setting->isDirty('is_public')) {
            $this->logActivity('visibility_changed', $setting, [
                'old' => $setting->getOriginal('is_public'),
                'new' => $setting->is_public,
            ]);
        }
    }

    public function deleted(Setting $setting): void
    {
        $this->clearCache();
        $this->logActivity('deleted', $setting);
    }

    protected function clearCache(): void
    {
        Cache::forget('settings.all');
        Cache::forget('settings.groups');
        Cache::forget('settings.public');
    }

    protected function logActivity(string $action, Setting $setting, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'setting_' . $action,
                'target_type' => 'setting',
                'target_id' => $setting->id,
                'metadata' => json_encode(array_merge([
                    'setting_key' => $setting->key,
                    'setting_group' => $setting->group,
                    'setting_type' => $setting->type,
                ], $additional)),
            ]);

            Log::info('Setting ' . $action, [
                'setting_id' => $setting->id,
                'setting_key' => $setting->key,
                'admin_id' => Auth::id(),
                'additional' => $additional,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log setting activity: ' . $e->getMessage());
        }
    }
}