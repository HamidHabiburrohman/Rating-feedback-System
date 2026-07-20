{{-- resources/views/components/admin/conversations/conversation-status.blade.php --}}
@props([
'status' => 'active',
'size' => 'sm',
])

@php
$statusMap = [
'open' => ['label' => 'Open', 'icon' => ''],
'active' => ['label' => 'Active', 'icon' => ''],
'pending' => ['label' => 'Pending', 'icon' => 'ti ti-clock'],
'closed' => ['label' => 'Closed', 'icon' => 'ti ti-x'],
'archived' => ['label' => 'Archived', 'icon' => 'ti ti-archive'],
'resolved' => ['label' => 'Resolved', 'icon' => 'ti ti-circle-check'],
];

$config = $statusMap[$status] ?? ['label' => ucfirst($status), 'icon' => ''];
$sizeClass = $size === 'sm' ? 'conv-pill-sm' : 'conv-pill-md';
@endphp

<span {{ $attributes->merge(['class' => "conv-status-pill {$sizeClass} conv-pill-{$status}"]) }}>
    @if($config['icon'])
    <i class="{{ $config['icon'] }}"></i>
    @endif
    {{ $config['label'] }}
</span>

@once
@push('styles')
<style>
    .conv-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        border-radius: 9999px;
        letter-spacing: 0.01em;
        white-space: nowrap;
        line-height: 1;
    }

    .conv-pill-sm {
        padding: 3px 8px;
        font-size: 11px;
    }

    .conv-pill-md {
        padding: 4px 10px;
        font-size: 12px;
    }

    .conv-status-pill i {
        font-size: 12px;
        stroke-width: 2;
    }

    .conv-pill-md i {
        font-size: 14px;
    }

    .conv-pill-open,
    .conv-pill-active {
        background: #ecfdf5;
        color: #059669;
    }

    .conv-pill-pending {
        background: #fffbeb;
        color: #d97706;
    }

    .conv-pill-closed {
        background: #fef2f2;
        color: #dc2626;
    }

    .conv-pill-archived {
        background: #f8fafc;
        color: #94a3b8;
    }

    .conv-pill-resolved {
        background: #eff6ff;
        color: #2563eb;
    }

</style>
@endpush
@endonce
