<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;

class FilterButtonComponent extends Component
{
    public $statusOptions;
    public $currentStatus;
    
    public function __construct($statusOptions = [], $currentStatus = [])
    {
        $this->statusOptions = $statusOptions;
        $this->currentStatus = $currentStatus;
    }

    public function render()
    {
        return view('components.admin.filter-button');
    }
}