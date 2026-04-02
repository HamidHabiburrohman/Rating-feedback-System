<?php

use App\Services\Admin\IconService;

if (!function_exists('unit_type_icon')) {
    function unit_type_icon($unitType, $class = 'w-6 h-6')
    {
        if (is_string($unitType)) {
            $iconKey = $unitType;
        } elseif (is_object($unitType) && isset($unitType->icon_key)) {
            $iconKey = $unitType->icon_key;
        } else {
            return '';
        }

        $iconService = app(IconService::class);
        return $iconService->getSvg($iconKey, ['class' => $class]);
    }
}

if (!function_exists('unit_type_icon_name')) {
    function unit_type_icon_name($unitType)
    {
        if (is_string($unitType)) {
            $iconKey = $unitType;
        } elseif (is_object($unitType) && isset($unitType->icon_key)) {
            $iconKey = $unitType->icon_key;
        } else {
            return '';
        }

        $iconService = app(IconService::class);
        $icon = $iconService->getIcon($iconKey);
        
        return is_array($icon) && isset($icon['name']) ? $icon['name'] : ucfirst($iconKey);
    }
}