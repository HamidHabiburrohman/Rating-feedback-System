<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;

class PerPageDropdownComponent extends Component
{
    public $options;
    public $currentPerPage;
    
    public function __construct($options = [10, 25, 50, 100], $currentPerPage = null)
    {
        $this->options = $options;
        $this->currentPerPage = $currentPerPage ?? request('per_page', 10);
    }

    public function render()
    {
        return view('components.admin.per-page-dropdown');
    }
}