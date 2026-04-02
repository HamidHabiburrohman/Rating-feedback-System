<?php

namespace App\View\Components\admin;

use App\Services\Admin\IconService;
use Illuminate\View\Component;

class UnitTypeIconComponent extends Component
{
    public string $iconKey;
    public ?string $svg;
    public array $attributes;

    /**
     * Create a new component instance.
     */
    public function __construct(string $iconKey, string $class = 'w-6 h-6', ?string $width = null, ?string $height = null)
    {
        $this->iconKey = $iconKey;
        
        $attributes = ['class' => $class];
        if ($width) $attributes['width'] = $width;
        if ($height) $attributes['height'] = $height;
        
        $this->attributes = $attributes;
        
        $iconService = app(IconService::class);
        $this->svg = $iconService->getSvg($iconKey, $attributes);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return function (array $data) {
            return $this->svg;
        };
    }
}