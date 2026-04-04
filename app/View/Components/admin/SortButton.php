<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortButton extends Component
{
    public $sortOptions;
    public $defaultSort;
    public $defaultOrder;

    public function __construct($sortOptions = [], $defaultSort = 'name', $defaultOrder = 'asc')
    {
        $this->sortOptions = $sortOptions;
        $this->defaultSort = $defaultSort;
        $this->defaultOrder = $defaultOrder;
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.sort-button');
    }
}