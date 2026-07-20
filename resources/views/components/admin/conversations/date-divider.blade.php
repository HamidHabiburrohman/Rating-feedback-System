{{-- resources/views/components/admin/conversations/date-divider.blade.php --}}
@props([
    'date' => now(),
    'sticky' => true,
])

@php
    $carbon = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
    
    if ($carbon->isToday()) {
        $formatted = 'Today';
    } elseif ($carbon->isYesterday()) {
        $formatted = 'Yesterday';
    } elseif ($carbon->isCurrentWeek()) {
        $formatted = $carbon->format('l');
    } else {
        $formatted = $carbon->format('M d, Y');
    }
@endphp

<div {{ $attributes->merge(['class' => 'conv-date-divider ' . ($sticky ? 'conv-date-sticky' : '')]) }}>
    <span class="conv-date-text">{{ $formatted }}</span>
</div>

@once
@push('styles')
<style>
.conv-date-divider {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 24px 0 16px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    user-select: none;
}
.conv-date-sticky {
    position: sticky;
    top: 0;
    z-index: 10;
    background: rgba(250, 250, 250, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 8px 0;
    margin: 0 0 16px;
    border-bottom: 1px solid transparent;
}
.conv-date-divider::before,
.conv-date-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}
.conv-date-text {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    letter-spacing: 0.02em;
    white-space: nowrap;
    text-transform: uppercase;
}
</style>
@endpush
@endonce