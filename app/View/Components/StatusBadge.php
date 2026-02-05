<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;
    public string $classes;

    /**
     * Create a new component instance.
     */
    public function __construct($status)
    {
        $this->status = $status;
        $this->classes = $this->getStatusClasses($status);
    }


    /**
     * Get default label based on status
     */
    private function getDefaultLabel(string $status): string
    {
        $labels = [
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Get CSS classes based on status
     */
    private function getStatusClasses(string $status): string
    {
        $statusColors = [
            'open' => 'bg-success-subtle text-success border border-success-subtle',
            'full' => 'bg-warning-subtle text-warning border border-warning-subtle',
            'maintenance' => 'bg-info-subtle text-info border border-info-subtle',
            'closed' => 'bg-danger-subtle text-danger border border-danger-subtle',
            'baru' => 'bg-primary-subtle text-primary border border-primary-subtle',
            'diproses' => 'bg-warning-subtle text-warning border border-warning-subtle',
            'selesai' => 'bg-success-subtle text-success border border-success-subtle',
            'ditolak' => 'bg-danger-subtle text-danger border border-danger-subtle'
        ];

        return $statusColors[$status] ?? 'bg-secondary-subtle text-secondary border border-secondary-subtle';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.components.status-badge');
    }
}