<?php

namespace App\View\Components\visitor;

use Illuminate\View\Component;

class ExportCardComponent extends Component
{
    public $type;
    public $title;
    public $count;
    public $gradient;
    public $icon;
    public $route;

    public function __construct($type, $title, $count, $gradient, $icon, $route)
    {
        $this->type = $type;
        $this->title = $title;
        $this->count = $count;
        $this->gradient = $gradient;
        $this->icon = $icon;
        $this->route = $route;
    }

    public function render()
    {
        return view('components.export-card');
    }
}