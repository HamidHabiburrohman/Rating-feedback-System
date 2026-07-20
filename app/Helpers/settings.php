<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return app(\App\Services\Admin\Setting\SettingService::class)->getValue($key, $default);
    }
}