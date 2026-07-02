<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GenerateModal extends Component
{
    public $units;

    public function __construct($units = [])
    {
        $this->units = $units;
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.generate-modal');
    }
}