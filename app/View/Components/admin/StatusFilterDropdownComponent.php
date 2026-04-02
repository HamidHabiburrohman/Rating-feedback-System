<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusFilterDropdownComponent extends Component
{
    public array $currentStatus;
    public string $resetStyle;
    public string $applyStyle;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentStatus = request('status') ? explode(',', request('status')) : [];
        $this->resetStyle = 'background: white; border: 1px solid #d1d5db; color: #4b5563;';
        $this->applyStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.status-filter-dropdown');
    }
}