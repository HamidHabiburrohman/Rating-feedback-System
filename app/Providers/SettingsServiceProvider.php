<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\Admin\SettingService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class);
    }
    
    public function boot(): void
    {
        $this->loadSettingsToConfig();
    }
    
    private function loadSettingsToConfig(): void
    {
        try {
            $settings = Setting::all();
            
            foreach ($settings as $setting) {
                Config::set("settings.{$setting->key}", $setting->value);
            }
            
            View::share('theme', [
                'primary' => config('settings.primary_color', '#3b82f6'),
                'secondary' => config('settings.secondary_color', '#64748b'),
            ]);
            
        } catch (\Exception $e) {
            // Database belum siap
        }
    }
}