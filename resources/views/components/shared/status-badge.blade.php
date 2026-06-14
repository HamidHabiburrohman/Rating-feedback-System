@props(['status' => 'active', 'size' => 'md'])

@php
$styleMap = [
    'pending_preview' => ['bg' => '#FEFCE8', 'text' => '#A16207', 'border' => '#FEF08A'],
    'new' => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFD8FE'],
    'in_progress' => ['bg' => '#FEFCE8', 'text' => '#A16207', 'border' => '#FEF08A'],
    'replied' => ['bg' => '#ECFEFF', 'text' => '#0E7490', 'border' => '#A5F3FC'],
    'resolved' => ['bg' => '#F0FDF4', 'text' => '#15803D', 'border' => '#BBF7D0'],
    'rejected' => ['bg' => '#FEF2F2', 'text' => '#B91C1C', 'border' => '#FECACA'],
    'active' => ['bg' => '#F0FDF4', 'text' => '#15803D', 'border' => '#BBF7D0'],
    'edited' => ['bg' => '#FAF5FF', 'text' => '#7C3AED', 'border' => '#E9D5FF'],
    'archived' => ['bg' => '#F9FAFB', 'text' => '#4B5563', 'border' => '#E5E7EB'],
    'open' => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFD8FE'],
    'full' => ['bg' => '#FFF7ED', 'text' => '#C2410C', 'border' => '#FED7AA'],
    'maintenance' => ['bg' => '#FAF5FF', 'text' => '#7C3AED', 'border' => '#E9D5FF'],
    'closed' => ['bg' => '#F9FAFB', 'text' => '#4B5563', 'border' => '#E5E7EB'],
    'inactive' => ['bg' => '#FEF2F2', 'text' => '#991B1B', 'border' => '#FECACA'],
    'low' => ['bg' => '#ECFDF5', 'text' => '#065F46', 'border' => '#A7F3D0'],
    'medium' => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFDBFE'],
    'high' => ['bg' => '#FFF7ED', 'text' => '#C2410C', 'border' => '#FED7AA'],
    'critical' => ['bg' => '#FEF2F2', 'text' => '#B91C1C', 'border' => '#FECACA'],
    'admin' => ['bg' => '#EFF6FF', 'text' => '#1E40AF', 'border' => '#BFDBFE'],
    'super_admin' => ['bg' => '#FAF5FF', 'text' => '#7C3AED', 'border' => '#E9D5FF'],
    'unit' => ['bg' => '#ECFEFF', 'text' => '#0E7490', 'border' => '#A5F3FC'],
];

$key = strtolower(trim($status));
$colors = $styleMap[$key] ?? ['bg' => '#F3F4F6', 'text' => '#1F2937', 'border' => '#D1D5DB'];

$sizeClass = match ($size) {
    'sm' => 'px-2 py-0 fs-small',
    'lg' => 'px-4 py-2 fs-6',
    default => 'px-3 py-2 fs-2',
};

$displayText = ucwords(str_replace('_', ' ', $status));
@endphp

<span {{ $attributes->class(["badge rounded-pill d-inline-flex align-items-center gap-1 {$sizeClass}"]) }}
    style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; border: 1px solid {{ $colors['border'] }};">
    {{ $displayText }}
</span>