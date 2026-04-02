<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonCreate extends Component
{
    public $url;
    public $modalId;
    public $onclick;
    public $tooltip;
    public $size;
    public $iconSize;
    public $defaultText;

    public function __construct(
        $url = null,
        $modalId = null,
        $onclick = null,
        $tooltip = null,
        $size = 'md',
        $iconSize = 18,
        $defaultText = 'Add New'
    ) {
        $this->url = $url;
        $this->modalId = $modalId;
        $this->onclick = $onclick;
        $this->tooltip = $tooltip;
        $this->size = $size;
        $this->iconSize = $iconSize;
        $this->defaultText = $defaultText;
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.button-create');
    }
}