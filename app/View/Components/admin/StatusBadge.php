<?php

namespace App\View\Components\admin;

use Illuminate\View\Component;
use Illuminate\View\View;

class StatusBadge extends Component
{
    public string $status;
    public ?string $type;
    public ?string $size;
    public ?string $extraClass;
    public string $sizeClass; // Tambahkan property ini
    public string $displayText; // Tambahkan property ini
    public string $inlineStyle; // Tambahkan property ini

    /**
     * Mapping status ke warna (hex) + border (hex) sesuai gambar design
     */
    protected array $styleMap = [
        'new'          => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFD8FE'],
        'in_progress'  => ['bg' => '#FEFCE8', 'text' => '#A16207', 'border' => '#FEF08A'],
        'replied'      => ['bg' => '#ECFEFF', 'text' => '#0E7490', 'border' => '#A5F3FC'],
        'resolved'     => ['bg' => '#F0FDF4', 'text' => '#15803D', 'border' => '#BBF7D0'],
        'rejected'     => ['bg' => '#FEF2F2', 'text' => '#B91C1C', 'border' => '#FECACA'],
        'active'       => ['bg' => '#F0FDF4', 'text' => '#15803D', 'border' => '#BBF7D0'],
        'edited'       => ['bg' => '#FAF5FF', 'text' => '#7C3AED', 'border' => '#E9D5FF'],
        'archived'     => ['bg' => '#F9FAFB', 'text' => '#4B5563', 'border' => '#E5E7EB'],
        'open'         => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFD8FE'],
        'full'         => ['bg' => '#FFF7ED', 'text' => '#C2410C', 'border' => '#FED7AA'],
        'maintenance'  => ['bg' => '#FAF5FF', 'text' => '#7C3AED', 'border' => '#E9D5FF'],
        'closed'       => ['bg' => '#F9FAFB', 'text' => '#4B5563', 'border' => '#E5E7EB'],
        'close'        => ['bg' => '#F9FAFB', 'text' => '#4B5563', 'border' => '#E5E7EB'],
        'inactive'     => ['bg' => '#FEF2F2', 'text' => '#991B1B', 'border' => '#FECACA'],
        'low'          => ['bg' => '#ECFDF5', 'text' => '#065F46', 'border' => '#A7F3D0'],
        'medium'       => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFDBFE'],
        'high'         => ['bg' => '#FFF7ED', 'text' => '#C2410C', 'border' => '#FED7AA'],
        'critical'     => ['bg' => '#FEF2F2', 'text' => '#B91C1C', 'border' => '#FECACA'],
        'admin'        => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFDBFE'],
        'super_admin'  => ['bg' => '#FAF5FF', 'text' => '#7C3AED', 'border' => '#E9D5FF'],
        'unit'         => ['bg' => '#ECFEFF', 'text' => '#0E7490', 'border' => '#A5F3FC'],
    ];

    public function __construct(string $status, ?string $type = null, ?string $size = null, ?string $class = null)
    {
        $this->status = $status;
        $this->type = $type;
        $this->size = $size;
        $this->extraClass = $class;
        
        // Set properties yang akan digunakan di blade
        $this->sizeClass = $this->getSizeClass();
        $this->displayText = $this->getDisplayText();
        $this->inlineStyle = $this->getInlineStyle();
    }

    /**
     * Ambil inline style berdasarkan status
     */
    protected function getInlineStyle(): string
    {
        $key = strtolower(trim($this->status));
        $colors = $this->styleMap[$key] ?? [
            'bg' => '#F3F4F6',
            'text' => '#1F2937',
            'border' => '#D1D5DB'
        ];

        return sprintf(
            'background-color: %s; color: %s; border: 1px solid %s;',
            $colors['bg'],
            $colors['text'],
            $colors['border']
        );
    }

    /**
     * Teks yang ditampilkan (human readable)
     */
    protected function getDisplayText(): string
    {
        $text = str_replace('_', ' ', $this->status);
        return ucwords($text);
    }

    /**
     * Ukuran badge (small, normal)
     */
    protected function getSizeClass(): string
    {
        return match ($this->size) {
            'sm'   => 'px-2 py-0 fs-small',
            'lg'   => 'px-4 py-2 fs-6',
            default => 'px-3 py-1 fs-6',
        };
    }

    public function render(): View
    {
        return view('components.admin.status-badge');
    }
}