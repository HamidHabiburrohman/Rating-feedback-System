<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;

class EmptyStateComponent extends Component
{
    public $message;
    public $submessage;
    public $icon;
    public $actionUrl;
    public $actionText;
    
    public function __construct(
        $message = 'No data found',
        $submessage = null,
        $icon = 'M20 12H4M12 4v16',
        $actionUrl = null,
        $actionText = 'Add New'
    ) {
        $this->message = $message;
        $this->submessage = $submessage;
        $this->icon = $icon;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    public function render()
    {
        return view('components.admin.empty-state');
    }
}