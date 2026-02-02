<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class alert extends Component
{
    public $type;
    public $title;
    public $message;
    public $dismissible;
    public $icon;
    public $autoHide;
    public $duration;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        $type = 'info',
        $title = null,
        $message = null,
        $dismissible = true,
        $icon = true,
        $autoHide = false,
        $duration = 5000
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->dismissible = $dismissible;
        $this->icon = $icon;
        $this->autoHide = $autoHide;
        $this->duration = $duration;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.components.alert');
    }
}