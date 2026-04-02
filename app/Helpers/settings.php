<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return app(\App\Services\Admin\SettingService::class)->getValue($key, $default);
    }
}