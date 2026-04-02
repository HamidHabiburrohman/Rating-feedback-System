<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $url;
    public $modalId;
    public $onclick;
    public $tooltip;
    public $disabled;
    public $disabledTooltip;

    public function __construct(
        $type = 'show',
        $url = null,
        $modalId = null,
        $onclick = null,
        $tooltip = null,
        $disabled = false,
        $disabledTooltip = null
    ) {
        $this->type = $type;
        $this->url = $url;
        $this->modalId = $modalId;
        $this->onclick = $onclick;
        $this->tooltip = $tooltip;
        $this->disabled = $disabled;
        $this->disabledTooltip = $disabledTooltip;
    }

    public function render()
    {
        return view('components.admin.button');
    }
}