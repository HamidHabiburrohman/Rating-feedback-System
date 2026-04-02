<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;

class ExportButtonsComponent extends Component
{
    public $formats;
    public $filters;
    public $exportRoute;
    public $label;
    public $buttonClass;
    public $buttonStyle;

    public function __construct(
        $formats = ['excel', 'pdf'],
        $filters = [],
        $exportRoute = '',
        $label = 'Export',
        $buttonClass = 'btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn',
        $buttonStyle = 'height: 44px; background-color: white; border-color: #d1d5db;'
    ) {
        $this->formats = $formats;
        $this->filters = $filters;
        $this->exportRoute = $exportRoute;
        $this->label = $label;
        $this->buttonClass = $buttonClass;
        $this->buttonStyle = $buttonStyle;
    }

    public function render()
    {
        return view('components.admin.export-button');
    }
}