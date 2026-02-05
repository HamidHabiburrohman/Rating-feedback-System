<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteModal extends Component
{
    public $id;
    public $title;
    public $itemName;
    public $itemType;
    public $deleteRoute;
    public $deleteMethod;

    public function __construct(
        $id, 
        $title = 'Delete Confirmation', 
        $itemName = '', 
        $itemType = 'item', 
        $deleteRoute = '', 
        $deleteMethod = 'DELETE'
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->itemName = $itemName;
        $this->itemType = $itemType;
        $this->deleteRoute = $deleteRoute;
        $this->deleteMethod = $deleteMethod;
    }

    public function render(): View|Closure|string
    {
        return view('layouts.components.delete-modal');
    }
}