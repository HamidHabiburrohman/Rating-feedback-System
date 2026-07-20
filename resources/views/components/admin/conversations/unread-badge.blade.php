{{-- resources/views/components/admin/conversations/unread-badge.blade.php --}}
@props([
'count' => 0,
'size' => 'md',
'animated' => false,
])

@if($count > 0)
@php
$displayCount = $count > 99 ? '99+' : (string)$count;
$sizeClass = $size === 'sm' ? 'conv-badge-sm' : ($size === 'lg' ? 'conv-badge-lg' : 'conv-badge-md');
$animClass = $animated ? 'conv-badge-animated' : '';
@endphp

<span {{ $attributes->merge(['class' => "conv-unread-badge {$sizeClass} {$animClass}"]) }}>
    {{ $displayCount }}
</span>
@endif

@once
@push('styles')
<style>
    .conv-unread-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #f8773c;
        color: #ffffff;
        border-radius: 9999px;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        letter-spacing: 0.02em;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(248, 119, 60, 0.2);
        white-space: nowrap;
    }

    .conv-badge-sm {
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        font-size: 10px;
    }

    .conv-badge-md {
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        font-size: 11px;
    }

    .conv-badge-lg {
        min-width: 28px;
        height: 28px;
        padding: 0 8px;
        font-size: 13px;
    }

    .conv-badge-animated {
        animation: conv-badge-pulse 2s infinite;
    }

    @keyframes conv-badge-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(248, 119, 60, 0.4);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(248, 119, 60, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(248, 119, 60, 0);
        }
    }

</style>
@endpush
@endonce
