<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;

class SortDropdownComponent extends Component
{
    public $options;
    public $currentSort;
    public $currentOrder;
    
    public function __construct($options = [], $currentSort = null, $currentOrder = null)
    {
        $this->options = $options;
        $this->currentSort = $currentSort ?? request('sort', 'created_at');
        $this->currentOrder = $currentOrder ?? request('order', 'desc');
    }

    public function render()
    {
        return view('components.admin.sort-dropdown');
    }
}