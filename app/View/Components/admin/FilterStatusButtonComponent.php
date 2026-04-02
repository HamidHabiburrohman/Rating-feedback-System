<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterStatusButtonComponent extends Component
{
    public string $label;
    public string $value;
    public array $currentStatus;
    public bool $isActive;
    public string $activeStyle;
    public string $inactiveStyle;

    /**
     * Create a new component instance.
     */
    public function __construct(string $label, string $value, array $currentStatus = [])
    {
        $this->label = $label;
        $this->value = $value;
        $this->currentStatus = $currentStatus;
        $this->activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
        $this->inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
        
        // Cek apakah button ini aktif
        if (empty($value)) {
            $this->isActive = empty($currentStatus);
        } else {
            $this->isActive = in_array($value, $currentStatus);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.filter-status-button');
    }
}