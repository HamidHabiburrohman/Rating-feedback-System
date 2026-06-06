<?php

namespace App\Services\Admin;

use App\Models\System\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    public function getAll(): Collection
    {
        return Setting::orderBy('group')->orderBy('sort_order')->get();
    }

    public function getByGroup(string $group): Collection
    {
        return Setting::where('group', $group)
            ->orderBy('sort_order')
            ->get();
    }

    public function get(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        return $setting->typed_value;
    }

    public function set(string $key, $value): bool
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return false;
        }

        $setting->value = $this->castValueToString($value);
        return $setting->save();
    }

    public function updateMultiple(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }

    protected function castValueToString($value): string
    {
        if (is_array($value)) {
            return json_encode($value);
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        return (string) $value;
    }
}